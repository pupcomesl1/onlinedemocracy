@extends('layouts_new.profileBase')

@section('title', $displayName)

@section('form')

  <h1>Score History</h1>

  <div class="graph" id="scoreHistoryGraph"></div>

  <table class="points-history">
    @foreach ($history as $item)
      <tr data-id="{{ $item['id'] }}">
        <td><strong class="text-{{ $item['value'] > 0 ? 'success' : 'danger' }}">
            {{ $item['value'] > 0 ? '+' : '' }}{{ $item['value'] }}
          </strong></td>
        <td>
          <small>{{ $item['running_total'] }}</small>
        </td>
        <td>{{ trans('messages.points.history.' . $item['cause']) }}</td>
        <td>{{ $item['created_at'] }}</td>
      </tr>
    @endforeach
  </table>

@endsection

@section('footer_scripts')
  <script>
      if (!Object.values) {
          Object.values = function values(O) {
              return reduce(keys(O), (v, k) => concat(v, typeof k === 'string' && isEnumerable(O, k) ? [O[k]] : []), []);
          };
      }
  </script>
  <script src="http://code.highcharts.com/highcharts.js"></script>
  <script src="http://code.highcharts.com/stock/highstock.js"></script>
  <script>
      var data = {!!   json_encode($history) !!};

      $(function () {
          var chart = Highcharts.chart('scoreHistoryGraph', {
              chart: {
                  type: 'line',
                  zoomType: 'x'
              },
              title: {
                  text: null
              },
              series: [{
                  name: 'Score History',
                  data: Object.values(data).map(function (item) {
                      console.log(item.created_at);
                      return {
                          name: item.created_at,
                          x: item.timeUnix * 1000,
                          y: item.running_total,
                          id: item.id
                      };
                  }),
              }],
              xAxis: {
                  type: 'datetime',
              },
              plotOptions: {
                  series: {
                      marker: {
                          enabled: false,
                          states: {
                              select: {
                                  fillColor: '#d8eabf',
                                  lineWidth: 1
                              }
                          }
                      },
                      point: {
                          events: {
                              mouseOver: function() {
                                  $('tr[data-id="' + this.id + '"]').addClass('hover');
                              },
                              mouseOut: function() {
                                  $('tr[data-id="' + this.id + '"]').removeClass('hover');
                              }
                          }
                      }
                  }
              },
              credits: {
                  enabled: false
              }
          });

          var highlight = function(id) {
              var p = chart.series[0].points.find(function(point) {return point.id == id});
              p.select();
          };
          $('tr[data-id]').hover(function() {
              $(this).addClass('hover');
              highlight($(this).data('id'));
          }, function() {
              $(this).removeClass('hover');
              highlight($(this).data('id'));
          });
      });
  </script>
@endsection