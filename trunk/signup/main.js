(function() {
    
    var translation = {
        'Username has already been taken' : '用户名已经有人使用',
        "Only use letters, numbers and '_'" : '只能使用英文字母、数字及下划线',
        'Email has already been taken.' : '邮箱已经有人使用'
    }

    var err = function(textbox, msg) {
        textbox.parent().find('span').addClass('error').html(msg);
    }
    
    var ok = function(textbox) {
        textbox.parent().find('span').removeClass('error').html('<img src="check.gif" alt="ok" />');
    }
    
    var reEmpty = /^\s*$/;
    var reEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;

    var urlencode = function(str) {
        return escape(str).replace('+', '%2B').replace('%20', '+').replace('*', '%2A').replace('/', '%2F').replace('@', '%40');
    }
    
    $('#user_name').blur(function() {
        var self = $(this);
        if (reEmpty.test(self.val())) {
            err(self, '姓名不能为空');
            return;
        }
        ok(self);
    });
    
    $('#user_screen_name').blur(function() {
        var self = $(this);
        if (reEmpty.test(self.val())) {
            err(self, '用户名不能为空');
            return;
        }
        $.getJSON('available.php?t=username&v=' + escape(self.val()), function(data) {
            var span = self.parent().find('span');
            if (data.valid) {
                ok(self);
            } else {
                err(self, translation[data.msg] || data.msg);
            }
        });
    });

    $('#user_email').blur(function() {
        var self = $(this);
        var email = self.val();
        if (!reEmail.test(email)) {
            err(self, '输入的邮箱地址不合法');
            return;
        }
        if (reEmpty.test(email)) {
            err(self, '邮箱不能为空');
            return;
        }
        $.getJSON('available.php?t=email&v=' + urlencode(email), function(data) {
            var span = self.parent().find('span');
            if (data.valid) {
                ok(self);
            } else {
                err(self, translation[data.msg] || data.msg);
            }
        });
    });
    
    $('#user_user_password').blur(function() {
        var self = $(this);
        if (self.val().length <= 6) {
            err(self, '密码长度不能小于6位');
            return;
        }
        ok(self);
    });

})();
