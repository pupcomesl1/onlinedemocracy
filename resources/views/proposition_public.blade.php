@extends('layouts_new.websiteBase')

@section('header_scripts')
  @include('layouts_new.proposition_share_info')
  <style>
    body {
      padding-top: 90px;
    }

    .navbar .btn {
      color: #eee !important;
      padding: 10px;
      margin: 10px;
    }

    .navbar .btn:hover, .navbar .btn:active, .navbar .btn:focus {
      color: #fff !important;
      background: #4283C5 !important;
    }

    .footer {
      display: none;
    }
  </style>

@stop

@section('title', $proposition['propositionSort'])

@section('content')

  <div class="container" id="main">
    <div class="row m-scene ">
      <div class="col-md-8 col-md-offset-2 scene_element scene_element--fadein">

        @if (session('status'))
          <div class="section">
            <div class="form-group form-group-sm">
              <div class="alert alert-warning" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          </div>
        @endif

        <div class="section">
          <a href="#" class="btn btn-default btn-sm" disabled><i
                class="fa fa-angle-left"></i> @lang('messages.proposition.back')</a>
          <span class="pull-right">
                <div class="btn-group">
                  <a href="#" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"><i
                        class="fa fa-share"></i> @lang('messages.proposition.share.share') <span class="badge"
                                                                                                 id="shares-count">0</span></a>
                  <ul class="dropdown-menu" id="social_links">
                    <li><a href="{{ $shareLinks['facebook'] }}"><i
                            class="fa fa-facebook-square"></i> @lang('messages.proposition.share.facebook')</a></li>
                    <li><a href="{{ $shareLinks['twitter'] }}"><i
                            class="fa fa-twitter-square"></i> @lang('messages.proposition.share.twitter')</a></li>
                    <li><a href="{{ $shareLinks['gplus'] }}"><i
                            class="fa fa-google-plus-square"></i> @lang('messages.proposition.share.gplus')</a></li>
                    <li><a href="{{ $shareLinks['pinterest'] }}"><i
                            class="fa fa-pinterest-square"></i> @lang('messages.proposition.share.pin')</a></li>
                  </ul>
                </div>
                <div class="btn-group">
	                @if ($proposition['ending_in'] <= 0)
                    <p class="label label-info label-btn">{{ Lang::get('messages.proposition.status.expired') }}</p>
                  @else
                    <p class="label label-info label-btn">{{ Lang::choice('messages.proposition.status.ending_in', $proposition['ending_in'], ['daysleft' => $proposition['ending_in']]) }}</p>
                  @endif
	         	</div>
                
            </span>
        </div>

        <div class="thumbnail proposition section">
          <div class="caption">
            <h1>@if (empty($proposition['marker']) == false)
                @if ($proposition['marker']->relationMarkerId() == \App\Marker::SUCCESS)<span
                    class="label label-success label-icon"><i class="material-icons">check</i></span>
                @elseif ($proposition['marker']->relationMarkerId() == \App\Marker::UNDER_DISCUSSION) <span
                    class="label label-info label-icon"><i class="material-icons">speaker_notes</i></span>
                @elseif ($proposition['marker']->relationMarkerId() == \App\Marker::FAILED) <span
                    class="label label-warning label-icon"><i class="material-icons">announcement</i></span>
                @endif
              @endif {{{ $proposition['propositionSort'] }}}</h1>
            <p class="lead">{{{ $proposition['propositionLong'] }}}</p>
            <small
                class="text-muted">{{ Lang::choice('messages.proposition.voting.stats.upvotes', $votes['upvotes'], ['votes' => $votes['upvotes']]) }}
              | {{ Lang::choice('messages.proposition.voting.stats.downvotes', $votes['downvotes'], ['votes' => $votes['downvotes']]) }}
              | {{ Lang::choice('messages.proposition.voting.stats.comments', $proposition['commentsCount'], ['comments' => $proposition['commentsCount']]) }}</small>
          </div>

          @if (empty($proposition['marker']) == false)
            @if ($proposition['marker']->relationMarkerId() == \App\Marker::SUCCESS)
              <div class="alert alert-success">
                <strong>@lang('messages.proposition.marker.1')!</strong> {{ $proposition['marker']->markerText() }}
              </div>
            @elseif ($proposition['marker']->relationMarkerId() == \App\Marker::UNDER_DISCUSSION)
              <div class="alert alert-info">
                <strong>@lang('messages.proposition.marker.2')!</strong> {{ $proposition['marker']->markerText() }}
              </div>
            @elseif ($proposition['marker']->relationMarkerId() == \App\Marker::FAILED)
              <div class="alert alert-warning">
                <strong>@lang('messages.proposition.marker.3')!</strong> {{ $proposition['marker']->markerText() }}
              </div>
            @endif
          @endif
        </div>

        @if ($proposition['ending_in'] <= 0)
          <div class="btn-group btn-group-justified section">
            <a href="#" class="btn btn-primary" disabled>{{ Lang::get('messages.proposition.voting.expired') }}</a>
          </div>
        @else

          <div class="btn-group btn-group-justified section">
            <a href="#" class="btn btn-success btn-text-lg" disabled><i class="material-icons"
                                                                        style="vertical-align: inherit;">thumb_up</i> {{ Lang::get('messages.proposition.voting.actions.upvote') }}
            </a>
            <a href="#" class="btn btn-danger btn-text-lg" disabled><i class="material-icons"
                                                                       style="vertical-align: middle;">thumb_down</i> {{ Lang::get('messages.proposition.voting.actions.downvote') }}
            </a>
          </div>
          <div class="btn-group btn-group-justified section">
            <a href="{{ tenantRoute('o365login') }}"
               class="btn btn-info btn-text-lg">{{ Lang::get('messages.proposition.voting.need_to_login') }}</a>
          </div>
        @endif


        <div class="section">
          <div class="thumbnail section">
            <div class="caption">
              <small class="text-muted" style="font-size: 90%;">
                {{ Lang::get('messages.proposition.voting.credits') }} <a href="#">
                  <img class="img-circle text-sized-picture" src="{{ $proposition['proposer']['avatar'] }}">
                  &nbsp;{{ $proposition['proposer']['displayName'] }}</a>
                <em>{{ $proposition['proposer']['points'] }}</em>
                @component('components.badge-counts', $proposition['proposer']['badges'])
                @endcomponent
                &nbsp;{{ $proposition['date_created'] }}</small>
            </div>
          </div>
        </div>

        <div class="section comments">
          <div class="thumbnail section">

            @if ($comments ==! 0)
              @foreach ($comments as $comment)
                <div
                    class="comment @if (isset($comment['distinguish'])) distinguish distinguish-{{ $comment['distinguish']['name'] }} @endif">
                  <small class="name">
                    @if (!$comment['modDeleted'] || (Auth::check() && Auth::user()->can('deleteComments')))
                      <strong>
                        <img class="img-circle text-sized-picture" src="{{ $comment['commenter']['avatar'] }}">
                        {{ $comment['commenter']['displayName'] }}
                      </strong>
                      <em>{{ $comment['commenter']['points'] }}</em>
                      @component('components.badge-counts', $comment['commenter']['badges'])
                      @endcomponent
                    @endif
                    @if (isset($comment['distinguish']))
                      <strong><em class="role">{{ $comment['distinguish']['display_name'] }}</em></strong>
                    @endif
                  </small>
                  <small class="pull-right text-muted">{{ $comment['date_created'] }}</small>
                  @if (!$comment['modDeleted'])
                    <p>{{ $comment['commentBody'] }}</p>
                  @else
                    <p><em>@lang('messages.proposition.comments.deleted')</em></p>
                  @endif
                </div>
              @endforeach
            @else
              <div class="caption">
                <small class="text-muted">@lang('messages.proposition.comments.no_comments').</small>
              </div>
            @endif

          </div>
        </div>


      </div>
    </div>
  </div>
@stop

@section('footer_scripts')
  <script>
      $(document).ready(function () {
          $("#social_links li a").click(function (e) {
              e.preventDefault();

              var link = $(this).attr('href');
              var myWindow = window.open(link, "MsgWindow", "width=550, height=500");
          });

          $URL = "{{ tenantRoute('proposition', [$proposition['id']]) }}";
          // Facebook Shares Count
          $.getJSON('https://graph.facebook.com/?id=' + $URL, function (fbdata) {
              $('#shares-count').text(ReplaceNumberWithCommas(fbdata.shares));
          });

      });

      // Format Number functions
      function ReplaceNumberWithCommas(yourNumber) {
          //Seperates the components of the number
          var components = yourNumber.toString().split(".");
          //Comma-fies the first part
          components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          //Combines the two sections
          return components.join(".");
      }
  </script>
  <script>
      (function (i, s, o, g, r, a, m) {
          i['GoogleAnalyticsObject'] = r;
          i[r] = i[r] || function () {
              (i[r].q = i[r].q || []).push(arguments)
          }, i[r].l = 1 * new Date();
          a = s.createElement(o),
              m = s.getElementsByTagName(o)[0];
          a.async = 1;
          a.src = g;
          m.parentNode.insertBefore(a, m)
      })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

      ga('create', 'UA-72457439-1', 'auto');
      ga('send', 'pageview');

  </script>
@stop