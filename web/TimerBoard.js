window.$(function() {
    TimerBoard.ready();
});

var TimerBoard = (function($, moment) {

    return {
        ready: function() {
            initSystemInput();
            showLocalTime();
            showRelativeTime();
        }
    };

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
                    timeZoneName: 'short',
                    year: 'numeric',
                    month: 'short',
                    weekday: 'short',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                }));
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
                    duration.days() + "d " +
                    duration.hours() + "h " +
                    duration.minutes() + "m"
                );
            });
        }
    }

})(window.$, window.moment);
