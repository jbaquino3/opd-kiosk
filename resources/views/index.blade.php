<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>OPD eKiosk</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="login-page" style="min-height: 512.391px;">
    <div id="app"></div>
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset('images/bghlogogif.gif') }}" class="brand-image img-circle elevation-3" height="200" width="200" style="opacity: .8">
        </div>
        <!-- login-box closes just like schrodinger's cat lmao -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Select OPD type of Consultation</p>
                <div class="row no-gutters">
                    <a class="btn btn-success btn-lg btn-block" href="/apex">APEX</a>
                    <a class="btn btn-primary btn-lg btn-block" href="/pcc">PRIMARY CARE CENTER</a>
                    <a class="btn btn-info btn-lg btn-block" href="/follow_up">FOLLOW UP PATIENTS</a>
                </div>
            </div>
        </div>
        <!-- u want a card for valentine's? -->


        <div style="font-size:14px">
            <a href="#help" data-toggle="modal"><span class="font-weight-bold text-danger">HELP | FAQ</span></a>
        </div>
        <div id="help" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header bg-dark">
                  <h5 class="modal-title">WHAT SHOULD I DO IF:</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="card card-body">
                      <span class="font-weight-bold">*I have encountered an error called "505" ?</span>
                      <span class="font-weight-bold">*I have encountered an error with codes that I don't understand?</span>
                      <span class="font-weight-bold">*I accidentally refreshed the page and an error occured?</span>
                      <span class="mt-3 font-weight-bold">Sol 1:</span>
                      <span class="ml-2">-Press <kbd>CTRL</kbd>+<kbd>F5</kbd> to hard-refresh the page.</span>
                      <span class="mt-3 font-weight-bold">Sol 2:</span>
                      <span class="ml-2">-Click the address bar and remove all the links, except 192.168.6.114</span>
                      <span class="ml-2">-Press <kbd>Enter</kbd></span>
                    </div>
                    <div class="card card-body">
                      <span class="font-weight-bold">*I have other concern about the system.</span>
                      <span class="ml-2">-Please contact MIS,local 202.</span>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-dismiss="modal">Thank you</button>
                </div>
              </div>
            </div>
          </div>
    </div>
</body>
</html>