@extends('layouts_new.websiteBase')

@section('title', 'Comment Guidelines - An online voting platform for schools')

@section('content')
  <div class="section terms">
    <div class="container">
      <h1>Comment Guidelines</h1>
      <blockquote>TL;DR: Be civil and constructive.</blockquote>
      <p>The petitions system provides a comment box so you can discuss propositions with your fellow schoolmates, exchange ideas, and work together to make the school a better place. However, there are some guidelines for using the system properly.</p>

      <p>The rules for the comments can be summarised in just four words: <b>be civil and constructive</b>. Let's examine that in more detail.</p>

      <h2>Be Civil</h2>
      <p><b>Address the argument, not the person.</b> We realise that it may be tempting to call someone an idiot, but it would be a lot better if you were to calmly explain why they're wrong, without resorting to nastiness, which doesn't help anyone. Disagreement is perfectly fine, but don't make it personal.</p>

      <p><b>Don't be intentionally rude.</b> On the Internet, it's much harder to gauge reactions than in real life, and it's much easier to sound rude than in real life. Take it easy, be nice, and you'll probably find that everyone else will do the same. If they're not, don't encourage them, flag the comment and move on.</p>

      <p><b>"He started it" is not a valid excuse.</b> We don't care who started it, the way the moderators see it, both of you did the crime, and now both of you will do the time.</p>

      <p><b>Don't feed the trolls.</b> If you see someone violating these rules, resist the urge to jump in and fight them over it. Instead, simply click the "Flag" link next to their comment, and one of our moderators will quickly turn up and deal with the situation.</p>

      <h2>Be Constructive</h2>
      <p><b>Avoid jokes and memes.</b> Jokes, banter, and humour make the discussion more light-hearted, however it tends to distract people from the matter at hand. The moderators may be tolerant of a little bit of humour, but if the discussion is getting sidetracked, irrelevant comments may be deleted in order to refocus it.</p>

      <p><b>Stay on topic.</b> Should be self-explanatory. If a discussion is getting sidetracked, the off-topic comments may be deleted. If it cannot stay on topic no matter what, it may be locked completely.</p>

      <p><b>"Meh" is not enough.</b> If you don't care about something, don't reply. Or if you do, at least explain why, which leads into the next point:</p>

      <p><b>No matter what your opinion, say why.</b> If you agree, say why. If you disagree, say why. If you don't really care, say why. If you can't be bothered to explain your opinion,  just move on and don't comment. </p>

      <p><b>If you want to just say yes or no, that's what the upvote and downvote buttons are for.</b> However, we'd all still prefer it if you left a comment explaining your reasons&mdash;it may (completely by accident) lead us to new ideas on how to make the school better</p>

      <h2>And the obvious:</h2>
      <p><b>Don't post racist, sexist, ageist, homophobic, or otherwise bigoted comments.</b></p>

      <p><b>Don't swear.</b></p>

      <p><b>Don't post threats or encourage self-harm.</b></p>

      <p><b>Don't post links to anything pornographic, illegal, or disgusting.</b></p>

      <p><b>Don't post anyone's personal information, including your own.</b></p>

      <p><b>Don't spam.</b></p>

      <p>The moderators can delete comments, lock discussions, and ban users at their own discretion if you don't follow the rules. <b>Don't rule-lawyer against them</b>, that just tends to make them angrier. If you legitimately think we were wrong, email <code>contact{{'@'}}{{ tenant()->id }}.directdemocracy.online</code>, however repeated rule-lawyering will lead to your pleas falling on deaf ears.</p>

      <p>We absolutely hope that we don't have to invoke these rules. We're all here to make the school a better place, and we hope you are too. To use a slightly labored analogy: Imagine the comments section as a party. Everyone is invited, and we want you all to have a good time. But if you walk in, jump on the table, start screaming, and then throw up on the carpet, don't be surprised if you're asked to leave.</p>

      <blockquote>-The Pupils' Committee</blockquote>

    </div>
  </div>
@stop

@section('footer_scripts')
  <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-72457439-1', 'auto');
      ga('send', 'pageview');

  </script>
@stop