window.$(function() {
    TimerBoard.ready();
});

var TimerBoard = (function($, moment) {

    return {
        ready: function() {
            initSystemInput();
            showLocalTime();
            showRelativeTime();
            $('[data-toggle="popover"]').popover();
            initPopoverActions();
        },
    };

    function initPopoverActions() {
        $('body').on('click', '.delete-event', function() {
            $('form[name="delete-event"]').submit();
        });
    }

    function initSystemInput() {
        var $input = $('input[name="system"]');
        if ($input.length !== 1) {
            return;
        }
        var options = {
            data: $input.data('systems'),
            list: {
                match: {
                    enabled: true
                },
                maxNumberOfElements: 30
            },
            theme: "dark"
        };
        $input.easyAutocomplete(options);
    }

    function showLocalTime() {
        $('.time-local').each(function() {
            var $col = $(this);
            var time = $col.data('time');
            if (time !== '') {
                var date = new Date(time * 1000);
                $col.text(date.toLocaleString(navigator.language, {
                    year: '2-digit',
                    month: 'short',
                    weekday: 'short',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                }));
                $col.attr('title', date.toLocaleString(navigator.language, {
                    year: '2-digit',
                    timeZoneName: 'short'
                }).split(' ')[1]); // it's like "18, MESZ" or "18년 GMT+2"
            }
        });
    }

    function showRelativeTime() {
        updateTime();
        setInterval(function() {
            updateTime();
        }, 5000); // 5 seconds

        function updateTime() {
            $('.time-relative').each(function() {
                var $col = $(this);
                var time = $col.data('time');
                if (time === '') {
                    return;
                }
                var diffTime = (time * 1000) - (new Date().getTime());
                var duration = moment.duration(Math.abs(diffTime), 'milliseconds');
                $col.text(
                    (diffTime < 0 ? '-' : '') +
                    (duration.years() > 0 ? duration.years() + "y " : "") +
                    (duration.months() > 0 ? duration.months() + "m " : "") +
                    (duration.days() > 0 ? duration.days() + "d " : "") +
                    (duration.hours() > 0 ? duration.hours() + "h " : "") +
                    duration.minutes() + "m"
                );
            });
        }
    }

})(window.$, window.moment);
