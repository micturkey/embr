			</tr>
          </tbody>
        </table>
		<div class="clear"></div>
			<div id="footer" class="round">
			<ul>
			<li>&copy; 2010 <a href="http://twitter.com/disinfeqt" target="_blank">disinfeqt</a></li>
			<?php if (BLOG_SITE) { ?><li><a href="<?php echo BLOG_SITE ?>" title="zdx Purified,JLHwung revised" target="_blank">Blog</a></li><?php }?>
			<li><a href="http://code.google.com/p/twitese/" target="_blank" title="Embr is proundly powered by the Open Source project - Twitese & Rabr">Twitese</a></li>
			<li><a href="http://code.google.com/p/embr/" target="_blank">Open Source</a></li>
			<?php if (SITE_OWNER) { ?><li>Run by <a href="http://twitter.com/<?php echo SITE_OWNER ?>" target="_blank"><?php echo SITE_OWNER ?></a></li><?php }?>
			<li><a class="share" title="Drag Me!" href="javascript:var%20d=document,w=window,f='<?php echo BASE_URL."/share.php" ?>',l=d.location,e=encodeURIComponent,p='?u='+e(l.href)+'&t='+e(d.title)+'&d='+e(w.getSelection?w.getSelection().toString():d.getSelection?d.getSelection():d.selection.createRange().text)+'&s=bm';a=function(){if(!w.open(f+p,'sharer','toolbar=0,status=0,resizable=0,width=600,height=300,left=175,top=150'))l.href=f+'.new'+p};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}void(0);">Share to Embr</a></li>
			<img src="<?php echo STAT_IMG ?>" stlyle="display:none"/>
			</ul>
			</div>
		</div>
	</div>
<script type="text/javascript">var nav=document.getElementById("primary_nav");var links=nav.getElementsByTagName("a");var currenturl=document.location.href;for(var i=0;i<links.length;i++){var linkurl=links[i].getAttribute("href");if(currenturl==links[i]){links[i].className="active";}}</script>
</body>
</html>
<?php ob_end_flush(); ?>