<!doctype html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.css">
    <link rel="stylesheet" href="scripts/style.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="scripts/moment.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/locale/fr.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        var tooltip = $('<div/>').qtip({
          id: 'test',
          content: {
            text: ' ',
            title: {
              button: true
            }
          },
          position: {
            my: 'bottom center',
            at: 'top center',
            target: 'event',
            viewport: $('#calendar'),
            adjust: {
              mouse: false,
              scroll: false
            }
          },
          style: 'qtip-light'
        }).qtip('api');

        $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          eventClick: function(data, event, view) {
            const description = data.description ? '<p>' + data.description + '<p>' : '';
            const content = '<h6>' + data.title + '</h6>' + description;

            tooltip.set({
              'content.text': content
            }).show(event);
          },
          viewDisplay: function() { tooltip.hide() },
          eventSources: [
            {
              url: 'feed.php',
              type: 'POST',
              error: function () {
                alert('there was an error while fetching events!');
              }
            }
          ],
          timeFormat: 'H(:mm)',
          locale: 'fr',
          weekends: false,
          allDay: true
        });
      });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div id="calendar"></div>
        </div>
        <div class="col-md-2" id="legende">
            <h5>Légende</h5>
            <div class="legendLine">
                <div class="colorbox conge"></div> Congés
            </div>
            <div class="legendLine">
                <div class="colorbox teletravail"></div> Télétravail
            </div>
            <div class="legendLine">
                <div class="colorbox ticket"></div> Deadline !
            </div>
        </div>
    </div>
</div>

</body>
</html>
