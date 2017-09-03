var SprintTemplate          =   '<div class="sprint-item">\
                                    <h3 class="title is-3">Sprint: {{Sprint}}</h3>\
                                    {{#if Tasks}}\
                                    <ul>\
                                    {{#each Tasks}}\
                                    <li class="task-item">\
                                        <a class="task-item-link is-block" href="#" data-task-id="{{ID}}">{{Title}}</a>\
                                        {{#if Description}}<p>{{{Description}}}</p>{{/if}}\
                                    </li>\
                                    {{/each}}\
                                    </ul>\
                                    {{else}}\
                                    <p>- no tasks -</p>\
                                    {{/if}}\
                                </div>',
    SprintItem              =   function(data)
    {
        this.tpl            =   Handlebars.compile(SprintTemplate);
        this.html           =   $($.trim(this.tpl(data)));

        this.html.find('.task-item-link').click(function(e)
        {
            e.preventDefault();
            var data        =   {
                                    'ID'    :   $(this).data('task-id'),
                                    'Title' :   $(this).html()
                                },
                tracking    =   new TrackingUI(data);
            tracking.show();
        });

        return this.html;
    };
