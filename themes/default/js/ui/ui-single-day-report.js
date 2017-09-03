var reportTemplate  =   '<div class="modal single-day-report is-active">\
                            <div class="modal-background"></div>\
                            <div class="modal-card core">\
                                <header class="modal-card-head">\
                                    <p class="modal-card-title">{{title}}</p>\
                                    <button class="delete" aria-label="close"></button>\
                                </header>\
                                <section class="modal-card-body">\
                                    {{#if tasks}}\
                                    <ul>\
                                    {{#each tasks}}\
                                        <li class="task columns is-mobile">\
                                            <div class="client column is-narrow">\
                                                <span class="task-badge">{{#if badge}}{{badge}}{{else}}{{client}}{{/if}}</span>\
                                            </div>\
                                            <span class="column task-title">{{title}}</span>\
                                            <span class="column task-duration is-3 has-text-right" data-duration="{{duration}}"></span>\
                                        </li>\
                                    {{/each}}\
                                    </ul>\
                                    {{else}}\
                                    <p>You haven\'t done anything today.</p>\
                                    {{/if}}\
                                </section>\
                                <footer class="modal-card-foot">\
                                    {{#if tasks}}\
                                    <div class="time-summary column is-paddingless"></div>\
                                    <div class="column is-narrow is-paddingless">\
                                        <button class="button is-success btn-report" data-endpoint="api/v/1/report/{{date}}" data-csrf="{{csrf}}">Report</button>\
                                        <button class="button btn-close">Cancel</button>\
                                    </div>\
                                    {{else}}\
                                    <button class="button btn-close">Cancel</button>\
                                    {{/if}}\
                                </footer>\
                            </div>\
                        </div>',
    ReportUI        =   function(data)
    {
        var me      =   this,
            total   =   0,
            timeFn  =   function(seconds)
            {
                if (seconds >= 3600) {
                    var hour    =   Math.round((seconds / 3600) * 100) / 100;
                    return hour + ' hour' + (hour > 1 ? 's' : '');
                } else if (seconds >= 60) {
                    var minute    =   Math.round((seconds / 60) * 100) / 100;
                    return minute + ' minute' + (minute > 1 ? 's' : '');
                }

                return seconds + ' second' + (seconds > 1 ? 's' : '');
            };
        this.tpl    =   Handlebars.compile(reportTemplate);
        this.html   =   $($.trim(this.tpl(data)));

        this.html.find('.task-duration').each(function(i, el)
        {
            $(this).html(timeFn($(this).data('duration')));
            total   +=  $(this).data('duration').toFloat();
        });

        total       =   Math.round((total / 3600) * 100) / 100;

        this.html.find('.time-summary').html( total + ' hour' + (total > 1 ? 's' : ''));

        this.html.find('.btn-close, .delete, .modal-background').click(function(e)
        {
            e.preventDefault();
            me.html.remove();
        });

        this.html.find('.btn-report').click(function(e)
        {
            e.preventDefault();
            $(this).addClass('is-loading');
            $.post(
                $(this).data('endpoint'),
                {
                    csrf: $(this).data('csrf')
                },
                function(data)
                {
                    me.html.find('.btn-close').click();
                    alert(data.Message);
                }
            );
        });

        return this.html;
    };
