var CalendarTemplate    =   '<div class="clndr-controls columns is-mobile">\
                                <div class="clndr-controls-button clndr-previous-button column has-text-right">&lsaquo;</div>\
                                <div class="column is-2 has-text-centered">\
                                    <span class="month">{{month}}</span>\
                                    <span class="year">{{year}}</span>\
                                </div>\
                                <div class="clndr-controls-button clndr-next-button column">&rsaquo;</div>\
                            </div>\
                            <div class="clndr-grid">\
                                <div class="days-of-the-week">\
                                    <div class="header-days columns is-mobile">\
                                    {{#each daysOfTheWeek}}\
                                        <div class="header-day column has-text-centered">{{this}}</div>\
                                    {{/each}}\
                                    </div>\
                                    <div class="days columns is-mobile is-multiline">\
                                    {{#each days}}\
                                        <div class="{{classes}} column">\
                                            <div class="day-num">\
                                                {{day}}\
                                            </div>\
                                            <div class="day-events">\
                                            {{#each events}}\
                                                <div class="task columns is-mobile{{#if isbutton}} has-more{{/if}}">\
                                                    {{#if isbutton}}\
                                                    <div class="column has-text-centered">{{title}}</div>\
                                                    {{else}}\
                                                    <div class="client column is-narrow">\
                                                        <span class="client-code">{{badge}}</span>\
                                                    </div>\
                                                    <span class="column task-title">{{title}}</span>\
                                                    {{/if}}\
                                                </div>\
                                            {{/each}}\
                                            </div>\
                                        </div>\
                                    {{/each}}\
                                    </div>\
                                </div>\
                            </div>';
