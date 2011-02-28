<?php
/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * Basic lib to work with Twitter's OAuth beta. This is untested and should not
 * be used in production code. Twitter's beta could change at anytime.
 *
 * Code based on:
 * Fire Eagle code - http://github.com/myelin/fireeagle-php-lib
 * twitterlibphp - http://github.com/jdp/twitterlibphp
 */

//require_once('config.php');
//require_once('oauth_lib.php');

/**
 * Twitter OAuth class
 */
class TwitterOAuth {
	/* Contains the last HTTP status code returned */
	public $http_code;
	/* Contains the last API call */
	public $last_api_call;
	/* Set up the API root URL */
	//public $host = "https://api.twitter.com/1/";
	public $host = API_URL;
	/* Set timeout default */
	public $timeout = 5;
	/* Set connect timeout */
	public $connecttimeout = 30;
	/* Verify SSL Cert */
	public $ssl_verifypeer = FALSE;
	/* Respons type */
	public $type = 'json';
	/* Decode returne json data */
	public $decode_json = TRUE;
	/* Immediately retry the API call if the response was not successful. */
	//public $retry = TRUE;
	public $source = 'embr';

	// user info
	public $username;
	public $screen_name;
	public $user_id;

	/**
	 * Set API URLS
	 */
	function accessTokenURL()  { return 'https://twitter.com/oauth/access_token'; }
	function authenticateURL() { return 'https://twitter.com/oauth/authenticate'; }
	function authorizeURL()    { return 'https://twitter.com/oauth/authorize'; }
	function requestTokenURL() { return 'https://twitter.com/oauth/request_token'; }

	/**
	 * Debug helpers
	 */
	function lastStatusCode() { return $this->http_status; }
	function lastAPICall() { return $this->last_api_call; }

	/**
	 * construct TwitterOAuth object
	 */
	function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
		$this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		if (!empty($oauth_token) && !empty($oauth_token_secret)) {
			$this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
			$this->screen_name = $_SESSION['access_token']['screen_name'];
			$this->username = $_SESSION['access_token']['screen_name'];
			$this->user_id = $_SESSION['access_token']['user_id'];
		} else {
			$this->token = NULL;
		}
	}


	/**
	 * Get a request_token from Twitter
	 *
	 * @returns a key/value array containing oauth_token and oauth_token_secret
	 */
	function getRequestToken($oauth_callback = NULL) {
		$parameters = array();
		if (!empty($oauth_callback)) {
			$parameters['oauth_callback'] = $oauth_callback;
		} 
		$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	/**
	 * Get the authorize URL
	 *
	 * @returns a string
	 */
	function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
		if (is_array($token)) {
			$token = $token['oauth_token'];
		}
		if (empty($sign_in_with_twitter)) {
			$url = $this->authorizeURL() . "?oauth_token={$token}";
		} else {
			$url = $this->authenticateURL() . "?oauth_token={$token}";
		}
		return $url;
	}

	/**
	 * Exchange the request token and secret for an access token and
	 * secret, to sign API calls.
	 *
	 * @returns array("oauth_token" => the access token,
	 *                "oauth_token_secret" => the access secret)
	 */
	function getAccessToken($oauth_verifier = FALSE) {
		$parameters = array();
		if (!empty($oauth_verifier)) {
			$parameters['oauth_verifier'] = $oauth_verifier;
		}
		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	/**
	 * GET wrappwer for oAuthRequest.
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if($response == false){
			return false;
		}
		if ($this->type == 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 */
	function post($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'POST', $parameters);
		if($response === false){
			return false;
		}
		if ($this->type === 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if($response === false){
			return false;
		}
		if ($this->type === 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request
	 */
	function oAuthRequest($url, $method, $parameters) {
		if ($url[0] == '/') { //non-twitter.com api shall offer the entire url.
			$url = "{$this->host}{$url}.{$this->type}";
		}
		$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
		switch ($method) {
		case 'GET':
			return $this->http($request->to_url(), 'GET');
		default:
			return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
		}
	}

	/**
	 * Make an HTTP request
	 *
	 * @return API results
	 */
	function http($url, $method, $postfields = NULL) {
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);

		switch ($method) {
		case 'POST':
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields)) {
				curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
			break;
		case 'DELETE':
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if (!empty($postfields)) {
				$url = "{$url}?{$postfields}";
			}
		}

		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->last_api_call = $url;
		curl_close ($ci);
		return $response;
	}

	/* ---------- API METHODS ---------- */
	/*                                   */
	/* ---------- Block ---------- */
	function blockingIDs(){
		$url = '/blocks/blocking/ids';
		return $this->get($url);
	}

	function blockingList($page){
		$url = '/blocks/blocking';
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	function blockUser($id){
		$url = "/blocks/create/$id";
		return $this->post($url);
	}

	function isBlocked($id){
		$url = "/blocks/exists/$id";
		return $this->get($url);
	}

	function unblockUser($id){
		$url = "/blocks/destroy/$id";
		return $this->delete($url);
	}

	/* ---------- Messages ---------- */
	function deleteDirectMessage($id){
		$url = "/direct_messages/destroy/$id";
		return $this->delete($url);
	}

	function directMessages($page = false, $since_id = false, $count = null){
		$url = '/direct_messages';
		$args = array();
		if( $since_id )
			$args['since_id'] = $since_id;
		if( $page )
			$args['page'] = $page;
		return $this->get($url, $args);
	}

	function sendDirectMessage($user, $text){
		$url = '/direct_messages/new';
		$args = array();
		$args['user'] = $user;
		if($text)
			$args['text'] = $text;
		return $this->post($url, $args);
	}

	function sentDirectMessage($page = false, $since = false, $since_id = false){
		$url = '/direct_messages/sent';
		$args = array();
		if($since)
			$args['since'] = $since;
		if($since_id)
			$args['since_id'] = $since_id;
		if($page)
			$args['page'] = $page;
		return $this->get($url, $args);
	}

	/* ---------- List ---------- */
	function addListMember($listid, $memberid){
		$url = "/$this->username/$listid/members";
		$args = array();
		if($memberid){
			$args['id'] = $memberid;
		}
		return $this->post($url, $args);
	}

	function beAddedLists($username = '', $cursor = false){
		$url = "/$username/lists/memberships";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function createList($name, $description, $isPortect){
		$url = "/$this->username/lists";
		$args = array();
		if($name){
			$args['name'] = $name;
		}
		if($description){
			$args['description'] = $description;
		}
		if($isProtect){
			$args['mode'] = 'private';
		}
		return $this->post($url, $args);
	}

	function createdLists($username = '', $cursor = false){
		$url = "/$username/lists";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function deleteList($id){
		$arr = explode('/', $id);
		$url = "/$arr[0]/lists/$arr[1]";
		return $this->delete($url);
	}

	function deleteListMember($id, $memberid){
		$arr = explode("/", $id);
		$url = "/$arr[0]/$arr[1]/members";
		$args = array();
		$args['list_id'] = $arr[1];
		if($memberid){
			$args['id'] = $memberid;
		}
		return $this->delete($url, $args);
	}

	function editList($prename, $name, $description, $isProtect){
		$url = "/$this->username/lists/$prename";
		$args = array();
		if($name){
			$args['name'] = $name;
		}
		if($description){
			$args['description'] = $description;
		}
		if($isProtect){
			$args['mode'] = "private";
		}
		return $this->post($url, $args);
	}

	function followedLists($username = '', $cursor = false){
		$url = "/$username/lists/subscriptions";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function followList($id){
		$arr = explode("/", $id);
		$url = "/$arr[0]/$arr[1]/subscribers";
		return $this->post($url, $args);
	}

	function isFollowedList($id){
		$arr = explode('/', $id);
		$url = "/$arr[0]/$arr[1]/subscribers/$this->username";
		return $this->get($url);
	}

	function listFollowers($id, $cursor = false){
		$arr = explode('/', $id);
		$url = "/$arr[0]/$arr[1]/subscribers";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function listInfo($id){
		$arr = explode('/', $id);
		$url = "/$arr[0]/lists/$arr[1]";
		return $this->get($url);
	}

	function listMembers($id, $cursor = false){
		$arr = explode("/", $id);
		$url = "/$arr[0]/$arr[1]/members";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);

	}

	function listStatus($id, $page = false, $since_id = false){
		$arr = explode('/', $id);
		$url = "/$arr[0]/lists/$arr[1]/statuses";
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($since_id){
			$args['since_id'] = $since_id;
		}
		return $this->get($url, $args);
	}

	function unfollowList($id){
		$arr = explode("/", $id);
		$url = "/$arr[0]/$arr[1]/subscribers";
		return $this->delete($url);
	}

	/* ---------- Friendship ---------- */
	function destroyUser($id){
		$url = "/friendships/destroy/$id";
		return $this->delete($url);
	}

	function followers($id = false, $page = false, $count = 30){
		$url = '/statuses/followers';
		$url .= $id ? "/$id" : "";
		if( $id )
			$args['id'] = $id;
		if( $count )
			$args['count'] = (int) $count;
		$args['cursor'] = $page ? $page : -1;
		return $this->get($url, $args);
	}

	function followUser($id, $notifications = false){
		$url = "/friendships/create/$id";
		$args = array();
		if($notifications)
			$args['follow'] = true;
		return $this->post($url, $args);
	}

	function friends($id = false, $page = false, $count = 30){
		$url = '/statuses/friends';
		$url .= $id ? "/$id" : "";
		$args = array();
		if( $id )
			$args['id'] = $id;
		if( $count )
			$args['count'] = (int) $count;
		$args['cursor'] = $page ? $page : -1;
		return $this->get($url, $args);
	}

	function isFriend($user_a, $user_b){
		$url = '/friendships/exists';
		$args = array();
		$args['user_a'] = $user_a;
		$args['user_b'] = $user_b;
		return $this->get($url, $args);
	}

	function friendship($source_screen_name,$target_screen_name){
		$url = '/friendships/show';
		$args = array();
		$args['source_screen_name'] = $source_screen_name;
		$args['target_screen_name'] = $target_screen_name;
		return $this->get($url, $args);
 	}
 	
	function relationship($target, $source = false){
		$url = '/friendships/show';
		$args = array();
		$args['target_screen_name'] = $target;
		if($source){
			$args['source_screen_name'] = $source;
		}
		return $this->get($url, $args);
	}

	function showUser($id = false, $email = false, $user_id = false, $screen_name = false){
		$url = '/users/show';
		$args = array();
		if($id)
			$args['id'] = $id;
		elseif($screen_name)
			$args['id'] = $screen_name;
		else
			$args['id'] = $this->user_id;

		return $this->get($url, $args);
	}

	/* ---------- Ratelimit ---------- */
	function ratelimit(){
		$url = '/account/rate_limit_status';
		return $this->get($url);
	}

	/* ---------- Retweet ---------- */
	function getRetweeters($id, $count = false){
		$url = "/statuses/retweets/$id";
		if($count != false){
			$url .= "?count=$count";
		}
		return $this->get($url);
	}

	function retweet($id){
		$url = "/statuses/retweet/$id";
		return $this->post($url);
	}

	function retweets($id, $count = 20){
		if($count > 100){
			$count = 100;
		}
		$url = "/statuses/retweets/$id";
		$args = array();
		$args['count'] = $count;
		return $this->get($url,$args);
	}

	// Returns the 20 most recent retweets posted by the authenticating user.
	function retweeted_by_me($page = false, $count = 20, $since_id = false, $max_id = false){
		$url = '/statuses/retweeted_by_me';
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	// Returns the 20 most recent retweets posted by the authenticating user's friends.
	function retweeted_to_me($page = false, $count = false, $since_id = false, $max_id = false){
		$url = '/statuses/retweeted_to_me';
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	function retweets_of_me($page = false, $count = false, $since_id = false, $max_id = false){
		$url = '/statuses/retweets_of_me';
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	/* ---------- Search ---------- */
	function search($q = false, $page = false, $rpp = false){
		$searchApiUrl = strpos($this->host, "twitter.com") > 0 ? "http://search.twitter.com" : $this->host;
 			$url = $searchApiUrl.'/search.'.$this->type;
		if(!$q)
			return false;
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($rpp){
			$args['rpp'] = $rpp;
		}
		$args['q'] = $q;
		return $this->get($url, $args);
	}

	/* ---------- Spam ---------- */
	function reportSpam($screen_name){
		$url = '/report_spam';
		$args = array();
		$args['screen_name'] = $screen_name;
		return $this->post($url, $args);
	}

	/* ---------- Timeline ---------- */
	function deleteStatus($id){
		$url = "/statuses/destroy/$id";
		return $this->delete($url);
	}

	function friendsTimeline($page = false, $since_id = false, $count = false){
		$url = '/statuses/friends_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($count)
			$args['count'] = $count;
		return $this->get($url, $args);
	}

	function getFavorites($page = false,$userid=false){
		if($userid == false){
			$url = '/favorites';
		}
		else{
			$url = '/favorites/'.$userid;
		}
		
		$args = array();
		if($page)
			$args['page'] = $page;
		return $this->get($url, $args);
	}

	function makeFavorite($id){
		$url = "/favorites/create/$id";
		return $this->post($url);
	}

	function publicTimeline($sinceid = false){
		$url = '/statuses/public_timeline';
		$args = array();
		if($sinceid){
			$args['since_id'] = $sinceid;
		}
		return $this->get($url, $args);
	}

	function removeFavorite($id){
		$url = "/favorites/destroy/$id";
		return $this->post($url);
	}

	function replies($page = false, $since_id = false){
		$url = '/statuses/mentions';
		$args = array();
		if($page)
			$args['page'] = (int) $page;
		if($since_id)
			$args['since_id'] = $since_id;
		return $this->get($url, $args);
	}

	function showStatus($id){
		$url = "/statuses/show/$id";
		return $this->get($url);
	}

	function update($status, $replying_to = false){
		try{
			$url = '/statuses/update';
			$args = array();
			$args['status'] = $status;
			if($replying_to)
				$args['in_reply_to_status_id'] = $replying_to;
			return $this->post($url, $args);
		}catch(Exception $ex){
			echo $ex->getLine." : ".$ex->getMessage();
		}
	}

	function userTimeline($page = false, $id = false, $count = false, $since_id = false, $include_rts = true){
		$url = '/statuses/user_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($id)
			$args['id'] = $id;
		if($count)
			$args['count'] = $count;
		if($since_id)
			$args['since_id'] = $since_id;
		if($include_rts)
			$args['include_rts'] = $include_rts;
		$response = $this->get($url, $args);
		if(isset($response->error))
		{
			if($response->error === 'Not authorized')
			{
				return 'protected';
			}
		}
		return $response;
	}

	function trends(){
		$url = "http://search.twitter.com/trends.$this->type";
		return $this->get($url);
	}

	/* ---------- Misc. ---------- */
	function twitterAvailable(){
		$url = "/help/test";
		if($this->get($url) == 'ok'){
			return true;
		}
		return false;
	}

	function updateProfile($fields = array()){
		$url = '/account/update_profile';
		$args = array();
		foreach( $fields as $pk => $pv ){
			switch( $pk ){
			case 'name' :
				$args[$pk] = (string) substr( $pv, 0, 20 );
				break;
			case 'email' :
				if( preg_match( '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $pv ) )
					$args[$pk] = (string) $pv;
				break;
			case 'url' :
				$args[$pk] = (string) substr( $pv, 0, 100 );
				break;
			case 'location' :
				$args[$pk] = (string) substr( $pv, 0, 30 );
				break;
			case 'description' :
				$args[$pk] = (string) substr( $pv, 0, 160 );
				break;
			default :
				break;
			}
		}
		return $this->post($url, $args);
	}

	function veverify(){
		$url = '/account/verify_credentials';
		return $this->get($url);
	}

	/* ---------- twitese method ---------- */
	function rank($page = false, $count = false){
		$url = TWITESE_API_URL."/rank.$this->type";
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($count){
			$args['count'] = $count;
		}
		return $this->get($url, $args);
	}

	function browse($page = false, $count = false){
		$url = TWITESE_API_URL."/browse.$this->type";
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($count){
			$args['count'] = $count;
		}
		return $this->get($url, $args);
	}
}

