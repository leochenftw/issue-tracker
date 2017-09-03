var TrackingTemplate    =   '<div class="ui-tracking has-text-centered">\
                                <div class="ui-tracking__icon btn-switch" href="#"><span class="icon"><i class="fa fa-bomb"></i></span></div>\
                                <h2 class="ui-tracking__title is-2 title">{{Title}}</h2>\
                                <p class="ui-tracking__status">\
                                    <a href="/api/v/1/tick-tock/{{ID}}" class="button is-info is-large is-loading">00:00:00</a>\
                                </p>\
                            </div>',
    TrackingUI          =   function(data)
    {
        var me          =   this,
            fn          =   function(e)
                            {
                                if ($(e.target).is('body')) {
                                    me.html.close();
                                }
                            },
            elapsed     =   function(start)
                            {
                                var time    =   Date.now() + time_shiv,
                                    diff    =   Math.round((time - start) * 0.001);
                                    timer   =   new Date(),
                                    hour    =   Math.floor(diff / 3600),
                                    min     =   Math.floor((diff % 3600) / 60),
                                    second  =  Math.floor((diff % 3600) % 60);

                                return hour.DoubleDigit() + ':' + min.DoubleDigit() + ':' + second.DoubleDigit();

                            },
            ajaxing     =   null,
            ticker      =   null,
            time_shiv   =   0;  // this is to keep the difference between the server time and the local time

        this.tpl        =   Handlebars.compile(TrackingTemplate);
        this.html       =   $($.trim(this.tpl(data)));

        this.html.show  =   function()
        {
            $('body').addClass('blurred bg-black').append(me.html);
            $(window).click(fn);
            var url     =   me.html.find('a.button').attr('href');

            ajaxing     =   $.get(url, function(data)
            {
                if (data) {
                    time_shiv    =   data.Now - Date.now(); // work out the difference between the server time and the local time

                    me.html.find('a.button').attr('href', url + '/' + data.FragmentID);
                    me.html.find('a.button').removeClass('is-info').addClass('is-danger').html(elapsed(data.Start));
                    ticker      =   setInterval(function ()
                    {
                        me.html.find('a.button').html(elapsed(data.Start));
                    }, 1000);
                } else {
                    me.html.find('a.button').removeClass('is-info').addClass('is-success');
                }

                me.html.find('a.button').removeClass('is-loading');
            });
        };

        this.html.close =   function()
        {
            if (ajaxing) {
                ajaxing.abort();
                ajaxing =   null;
            }

            if (ticker) {
                clearInterval(ticker);
                ticker  =   null;
            }

            $('body').removeClass('blurred bg-black');
            me.html.remove();
            $(window).unbind('click', fn);
        };

        this.html.find('a.button').click(function(e)
        {
            e.preventDefault();

            if (ajaxing) {
                ajaxing.abort();
                ajaxing =   null;
            }

            var btn             =   $(this);
            btn.addClass('is-loading');
            ajaxing             =   $.post($(this).attr('href'), function(data)
            {
                btn.removeClass('is-loading');
                if (data.Action == 'stop') {
                    me.html.close();
                    alert(data.Message);
                } else {
                    var href    =   btn.attr('href');
                    href        =   href.replace(/\/+$/,'') + '/' + data.FragmentID;
                    btn.attr('href', href);

                    btn.removeClass('is-success').addClass('is-danger');
                    var now     =   Date.now();

                    ticker      =   setInterval(function ()
                    {
                        btn.html(elapsed(now));
                    }, 1000);
                }
            });
        });

        return this.html;
    };
