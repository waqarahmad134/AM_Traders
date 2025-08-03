@extends('welcome')
@section('content')

@section('title', 'Edit Profile | Admin')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.0/css/intlTelInput.css" />
<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Edit Profile</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Edit Profile</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Edit Profile</h2>
                    </div>
                    <div class="body">
                        <form action="{{route('update_profile')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col">
                                    <label for="lastName">Name</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control" id="lastName" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>
                            </div>
                            <div class="form-group">
                                <label for="phoneNum">Phone Number</label>
                                <input class="form-control tel" type="tel" name="leyka_donor_phone" inputmode="tel" value="{{ old('leyka_donor_phone', Auth::user()->contact) }}" />
                                <input class="form-control tel" id="countrycode" type="hidden" name="countrycode" value="92" required />
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.0/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.8/jquery.inputmask.bundle.min.js"></script>
<script>
    $j = jQuery.noConflict();

    $j(function() {
        var input = document.querySelectorAll("input[name=leyka_donor_phone]");
        var iti_el = $j(".iti.iti--allow-dropdown.iti--separate-dial-code");
        if (iti_el.length) {
            iti.destroy();
        }
        for (var i = 0; i < input.length; i++) {
            iti = intlTelInput(input[i], {
                autoHideDialCode: false,
                autoPlaceholder: "aggressive",
                initialCountry: "pk",
                separateDialCode: true,
                preferredCountries: ["ru", "th"],
                customPlaceholder: function(
                    selectedCountryPlaceholder,
                    selectedCountryData
                ) {
                    return "" + selectedCountryPlaceholder.replace(/[0-9]/g, "X");
                },
                geoIpLookup: function(callback) {
                    $j.get("https://ipinfo.io", function() {}, "jsonp").always(
                        function(resp) {
                            var countryCode = resp && resp.country ? resp.country : "";
                            callback(countryCode);
                        }
                    );
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.0/js/utils.js",
            });

            $j('input[name="leyka_donor_phone"]').on(
                "focus click countrychange",
                function(e, countryData) {
                    var pl = $j(this).attr("placeholder") + "";
                    var res = pl.replace(/X/g, "9");
                    if (res != "undefined") {
                        $j(this).inputmask(res, {
                            placeholder: "X",
                            clearMaskOnLostFocus: true,
                        });
                    }
                }
            );

            $j('input[name="leyka_donor_phone"]').on(
                "focusout",
                function(e, countryData) {
                    var intlNumber = iti.getNumber();
                    console.log(intlNumber);
                    console.log(iti.s.dialCode);
                    $('#countrycode').val(iti.s.dialCode);
                }
            );
        }
    });
</script>

@endsection
