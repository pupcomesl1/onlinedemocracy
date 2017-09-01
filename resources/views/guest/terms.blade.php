@extends('layouts_new.websiteBase')

@section('title', 'Terms of service - An online voting platform for schools')

@section('content')
  <div class="section terms">
    <div class="container">
      <h1>Terms of service</h1>
      <p class="lead text-muted">August 2017 Version</p>

      <p>The Petitions Software Application and Service (hereinafter "Petitions") is provided and maintained by Pupils'
        Committee ESL 1.</p>
      <p>By using Petitions you are agreeing to be bound by the following terms and conditions ("Terms of Service"). If
        you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials
        contained in this website are protected by applicable copyright and trademark law.</p>
      <p>If Pupils' Committee ESL 1 makes material changes to these Terms, we will notify you by email or by posting a
        notice on our site before the changes are effective. Any new features that augment or enhance the current
        Service, including the release of new tools and resources, shall be subject to the Terms of Service. Continued
        use of Petitions after any such changes shall constitute your consent to such changes. You can review the most
        current version of the Terms of Service at any time at: <a href="{{ route('terms') }}">{{ route('terms') }}</a>
      </p>
      <p>Violation of any of the terms below may result in the termination of your Account. While Pupils' Committee ESL
        1 prohibits such conduct and Content on Petitions, you understand and agree that Pupils' Committee ESL 1 cannot
        be responsible for the Content posted on Petitions by Third Parties and you nonetheless may be exposed to such
        materials. You agree to use Petitions at your own risk.</p>

      <h3>Terminology</h3>
      <p><strong>Petitions</strong>: The online platform, accessible at {{ Request::root() }}, provided by Pupils'
        Committee ESL 1.</p>
      <p><strong>User</strong>: A physical person or legal entity who has access via registration to Petitions. A User
        can vote, comment on propositions, and create propositions. Users must belong to the European School of
        Kirchberg in order to be able to perform such actions.</p>
      <p><strong>Proposition</strong>: A suggestion about the functioning or the daily life of the school proposed by a
        User and submitted for voting by other Users.</p>
      <p><strong>Moderator</strong>: A User who has the rights to approve or block propositions before they become
        public. Moderators can also handle flag requests made by other Users.</p>
      <p><strong>Administrator</strong>: A User who has control over the servers used to provide the Petitions service.
        Administrators are generally, although not necessarily, members of Pupils' Committee ESL 1.</p>
      <p><strong>Content</strong>: Material created by Users when using Petitions.</p>

      <h2>1. Use of the service</h2>
      <p>Your login may only be used by one person, a single login by multiple people is not permitted.</p>
      <p>You are responsible for maintaining the security of your account and password. Pupils' Committee ESL 1 cannot
        and will not be held liable for any loss or damage from your failure to comply with this security
        obligation.</p>
      <p>You are responsible for all Content posted and activity that occurs under your account and the Propositions
        created by you. You may not use Petitions for any illegal or unauthorized purpose. You must not, in the use of
        Petitions, violate any laws in its jurisdiction (including but not limited to copyright or trademark laws).</p>

      <h2>2. Copyright and Ownership of content</h2>
      <p>You agree that all Content uploaded or submitted to Petitions is irrevocably licensed to Pupils' Committee ESL
        1 under the Creative Commons Attribution Share-Alike License. You grant Pupils' Committee ESL 1 the perpetual
        and irrevocable right and license to use, store, copy, cache, publish, distribute, modify, and create derivative
        works of Content and, except as otherwise set forth herein, allow others to do so in any medium now known or
        hereinafter developed in order to provide Petitions, even if the Content has been uploaded or submitted and
        subsequently removed by You. You warrant, represent, and agree that You have the right to grant Pupils'
        Committee ESL 1 the rights set forth above.</p>
      <p>Pupils' Committee ESL 1 may or may not pre-screen Content, but Pupils' Committee ESL 1 reserves the right in
        their sole discretion to refuse or remove any Content that is available via Petitions.</p>

      <h2>3. Cancellation, deletion and termination</h2>
      <p>You may request to delete your account at any moment. Your Content will remain licensed to Pupils' Committee
        ESL 1 as stated above.</p>
      <p><strong>Pupils' Committee ESL 1 in its sole discretion has the right to suspend or terminate your Account and
          refuse any and all current or future use of Petitions in case of non-respect of the present agreement at any
          time. Such termination of your Account will result in the deactivation or deletion of your Account or access
          to your Account, and the forfeiture and relinquishment of all Content in your Account. Pupils' Committee ESL 1
          reserves the right to refuse service to anyone for any reason at any time.</strong></p>

      <h2>4. Intellectual property</h2>
      <p>Pupils' Committee ESL 1 reserves all intellectual property rights of Petitions, including those attached to
        the underlying Software Source Code. Portions of Petitions and its constituent Software parts are
        copyright © Photis Avrilionis 2015-2017, all other portions are copyright © Pupils' Committee ESL 1 2017-2017.
        All rights reserved. These intellectual property rights are related to the protection of software in particular
        as defined in article 1, paragraph 1 as well as article 31 of the <i>loi modifiée du 18 avril 2001 sur les
          droits d’auteur, les droits voisins et les bases de données</i> of the Grand Duchy of Luxembourg. The software
        is licensed under the <a href="{{ route('license') }}">MIT license</a>.</p>
      <p>You may not duplicate, copy, or reuse any portion of the HTML/CSS, Javascript, or visual design elements or
        concepts without express written permission from Pupils' Committee ESL 1.</p>

      <h2>5. General conditions</h2>
      <p>You agree to use Petitions at your sole risk. The Service is provided on an "AS IS" and "AS AVAILABLE"
        basis.</p>
      <p>The Service is available in English and French. Other languages may be available in the future. Pupils'
        Committee ESL 1 reserves the right to at any time add or remove languages with or without notice.</p>
      <p>Support for Petitions is available in English or French, via email.</p>
      <p>You understand that Pupils' Committee ESL 1 uses third-party vendors and hosting partners to provide the
        necessary hardware, software, networking, storage, and related technology required to run Petitions.</p>
      <p>The Service is delivered over the cloud. You have no obligation to install special software in order to use the
        Service besides a web browser. Petitions is primarily developed for use with the Google Chrome browser. Other
        browsers are supported on a best effort basis.</p>
      <p>Pupils' Committee ESL 1 will make its best efforts to ensure an acceptable level of service for the Users.
        Pupils' Committee ESL 1 reserves the right to migrate the service to different cloud providers to guarantee the
        best conditions for the delivery of Petitions.</p>
      <p>You must not modify, adapt or hack Petitions or modify another website so as to falsely imply that it is
        associated with Petitions or Pupils' Committee ESL 1.</p>
      <p>Pupils' Committee ESL 1 may, but has no obligation to, remove Content and Accounts containing Content that
        Pupils' Committee ESL 1 determines in its sole discretion is unlawful, offensive, threatening, libellous,
        defamatory, pornographic, obscene, or otherwise objectionable or violates any party's intellectual property or
        these Terms of Service.</p>
      <p>Verbal, physical, written or other abuse (including threats of abuse or retribution) of any Petitions user,
        proposition or comment may result in immediate account termination.</p>
      <p>You understand that the technical processing and transmission of Petitions, including your Content, may be
        transferred encrypted or unencrypted and involve (a) transmissions over various networks; and (b) changes to
        conform and adapt to technical requirements of connecting various networks or devices.</p>
      <p>The materials appearing on Petitions could include technical, typographical, or photographic errors. Pupils'
        Committee ESL 1 does not warrant that any of the materials on Petitions are accurate, complete or current.
        Pupils' Committee ESL 1 may make changes to the materials contained on Petitions at any time without notice.
        However Pupils' Committee ESL 1 does not make any commitment to update the materials.</p>
      <p>Pupils' Committee ESL 1 does not warrant that (a) Petitions will meet your specific requirements, (b) Petitions
        will be uninterrupted, timely, secure, or error-free, (c) the results that may be obtained from the use
        of Petitions will be accurate or reliable, (d) the quality of any products, services, information, or other
        material purchased or obtained by you through Petitions will meet your expectations, and (e) any errors in
        Petitions will be corrected.</p>
      <p>You expressly understand and agree that Pupils' Committee ESL 1 shall not be liable for any direct, indirect,
        incidental, special, consequential or exemplary damages, including but not limited to, damages for loss of
        goodwill, use, data or other intangible losses (even if Pupils' Committee ESL 1 or an authorised Pupils'
        Committee ESL 1 representative has been advised of the possibility of such damages), resulting from: (a) the use
        or the inability to use Petitions; (b) the cost of procurement of substitute goods and services resulting from
        any goods, data, information or services purchased or obtained or messages received or transactions entered into
        through or from Petitions; (c) unauthorized access to or alteration of your transmissions or data; (d)
        statements or conduct of any third-party on Petitions; (e) or any other matter relating to Petitions.</p>
      <p>The failure of Pupils' Committee ESL 1 to exercise or enforce any right or provision of the Terms of Service
        shall not constitute a waiver of such right or provision. The Terms of Service constitutes the entire agreement
        between you and Pupils' Committee ESL 1 and governs your use of Petitions, superseding any prior agreements
        between you and Pupils' Committee ESL 1 (including, but not limited to, any prior versions of the Terms of
        Service).</p>

      <h2>5. Jurisdiction</h2>

      <p>You agree that these Terms of Service and Your use of Petitions are governed under the laws of the Grand
        Duchy of Luxembourg. You consent to seek an agreed settlement to any difficulty, which may arise with respect to
        the application or interpretation of the contractual clauses.</p>
      <p>Any difficulties with respect to the interpretation, performance or termination of this agreement shall, in the
        absence of an agreed settlement, be brought before the Court of Luxembourg, to which territorial jurisdiction is
        hereby attributed, even in the event of third party proceedings or multiple defendants.</p>

    </div>
  </div>
@stop

@section('footer_scripts')
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

      ga('create', 'UA-70133844-9', 'auto');
      ga('send', 'pageview');

  </script>
@stop