Handlebars.registerHelper('ifEqual', function(a, b, options) {
    if(a === b) {
        return options.fn(this);
    }
    return options.inverse(this);
});

$(document).ready(function(e)
{
    BulmaAlert();
    $('.ajax-content-tasks').afetch(function(data, listTo, navTo)
    {
        data.forEach(function(o)
        {
            var sprint  =   new SprintItem(o);
            listTo.append(sprint);
        })
    });

    if ($('.calendar').length > 0) {
        moment.locale('nz');
        var calendar = $('.calendar').clndr({
            moment: moment,
            weekOffset: 1,
            events: [],
            daysOfTheWeek: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            clickEvents: {
                click: function(target)
                {
                    var endpoint    =   $('.calendar').data('endpoint'),
                        year        =   target.date.format('YYYY'),
                        month       =   target.date.format('MM'),
                        date        =   target.date.format('DD');

                    $.get(
                        endpoint + year + '/' + month + '/' + date,
                        function(data)
                        {
                            var report = new ReportUI(data);
                            $('body').append(report);
                        }
                    );

                },
                onMonthChange: function(month)
                {
                    calendar.options.events = [];
                    $('.calendar').data('year', month.format('YYYY'));
                    $('.calendar').data('month', month.format('MM'));
                    fetchDate();
                }
            },
            render: function (data) {
                return Handlebars.compile(CalendarTemplate)(data);
            }
        });

        var fetchDate   =   function()
        {
            var endpoint    =   $('.calendar').data('endpoint'),
                Today       =   new Date();
                year        =   $('.calendar').data('year') ? $('.calendar').data('year') : Today.getFullYear(),
                month       =   $('.calendar').data('month') ? $('.calendar').data('month') : (Today.getMonth() + 1).DoubleDigit();

            $.get(
                endpoint + year + '/' + month,
                function(data)
                {
                    calendar.addEvents(data);
                }
            );
        };

        fetchDate();
    }
});
