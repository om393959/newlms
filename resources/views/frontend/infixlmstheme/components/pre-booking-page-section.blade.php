<style>
    .modal-dialog {
        max-width: 1000px;
    }
</style>
<div class="main_content_iner main_content_padding">

    <div class="dashboard_lg_card">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12">
                    <div class="p-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="section__title3 mb_40">
                                    <h3 class="mb-0">{{__('payment.Pre Booking')}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="billing_details_wrapper">
                                    <div class="biling_address gray-bg  p-4">
                                        <h4>{{ __('courses.Course') }} : {{@$course->title}}</h4>
                                        <h4>{{ __('frontend.Pre Booking Amount') }} : {{@$amount}}</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($amount))
                            <div class="row">
                                <div class="col-12">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="section__title3 mb_40">
                                                    <h3 class="mb-0"> {{__('payment.Payment Method')}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">

                                                <div class="deposit_lists_wrapper mb-50">
                                                    @if(isset($methods))
                                                        @foreach($methods as $key=>$gateway)
                                                            @php
                                                                if (!paymentGateWayCredentialsEmptyCheck($gateway->method)){
                                                                continue;
                                                                }
                                                            @endphp
                                                            <div
                                                                class="single_deposite {{$gateway->method=="Wallet"?'p-0 border-0':''}}">

                                                                @if($gateway->method=="Stripe")
                                                                    <form
                                                                        action="{{route('upcoming_courses.prebooking.submit')}}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="method"
                                                                               value="{{$gateway->method}}">
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{$course->id}}">
                                                                        <input type="hidden" name="deposit_amount"
                                                                               value="{{$amount}}">
                                                                        <!-- single_deposite_item  -->
                                                                        <button type="submit" class="">
                                                                            <img
                                                                                src="{{asset($gateway->logo)}}"
                                                                                alt="">
                                                                        </button>
                                                                        @csrf
                                                                        <script
                                                                            src="https://checkout.stripe.com/checkout.js"
                                                                            class="stripe-button"
                                                                            data-key="{{ getPaymentEnv('STRIPE_KEY') }}"
                                                                            data-name="Stripe Payment"
                                                                            data-image="{{asset(Settings('favicon') )}}"
                                                                            data-locale="auto"
                                                                            data-currency="usd">
                                                                        </script>


                                                                    </form>

                                                                @elseif($gateway->method=="MercadoPago")

                                                                    <div class="">

                                                                        <a href="#" data-bs-toggle="modal"
                                                                           data-bs-target="#MakePaymentFromCreditMercadoPago"
                                                                           class=" ">
                                                                            <img class=" w-100" style="    padding: 0;
                                                                    margin-top: -2px;"
                                                                                 src="{{asset($gateway->logo)}}"
                                                                                 alt="">
                                                                        </a>
                                                                    </div>


                                                                    <div class="modal fade "
                                                                         id="MakePaymentFromCreditMercadoPago"
                                                                         tabindex="-1"
                                                                         role="dialog"
                                                                         aria-labelledby="exampleModalLabel"
                                                                         aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg"
                                                                             role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="">MercadoPago</h5>
                                                                                </div>


                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        @php
                                                                                            $total_amount =$amount;
                                                                                            $route =route('upcoming_courses.prebooking.submit');
                                                                                            $payment_type ='prebooking'
                                                                                        @endphp
                                                                                        <div class="col-md-12">
                                                                                            @include('mercadopago::partials._checkout',compact('total_amount','payment_type'))
                                                                                        </div>
                                                                                    </div>


                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                @elseif($gateway->method=="RazorPay")

                                                                    @csrf

                                                                    <div class="single_deposite_item">

                                                                        <div class="deposite_button text-center">
                                                                            <form
                                                                                action="{{ route('upcoming_courses.prebooking.submit') }}"
                                                                                method="POST">
                                                                                <input type="hidden" name="method"
                                                                                       value="{{$gateway->method}}">
                                                                                <input type="hidden"
                                                                                       name="deposit_amount"
                                                                                       value="{{$amount}}">
                                                                                <input type="hidden" name="course_id"
                                                                                       value="{{$course->id}}">
                                                                                <button type="button"
                                                                                        class="">
                                                                                    <img class="submitBtn"
                                                                                         src="{{asset($gateway->logo)}}"
                                                                                         alt="">
                                                                                </button>
                                                                                @csrf
                                                                                <script
                                                                                    src="https://checkout.razorpay.com/v1/checkout.js"
                                                                                    data-key="{{ getPaymentEnv('RAZOR_KEY') }}"
                                                                                    data-amount="{{ convertCurrency(Settings('currency_code') ??'BDT', 'INR', $amount)*100}}"
                                                                                    data-name="{{Settings('site_title') }}"
                                                                                    data-description="Cart Payment"
                                                                                    data-image="{{asset(Settings('favicon') )}}"
                                                                                    data-prefill.name="{{ @Auth::user()->username }}"
                                                                                    data-prefill.email="{{ @Auth::user()->email }}"
                                                                                    data-theme.color="#ff7529">
                                                                                </script>
                                                                            </form>
                                                                        </div>
                                                                    </div>

                                                                @elseif($gateway->method=="PayPal")

                                                                    <form
                                                                        action="{{route('upcoming_courses.prebooking.submit')}}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="method"
                                                                               value="{{$gateway->method}}">
                                                                        <input type="hidden" name="deposit_amount"
                                                                               value="{{$amount}}">
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{$course->id}}">
                                                                        <button type="submit" class="">
                                                                            <img class=""
                                                                                 src="{{asset($gateway->logo)}}"
                                                                                 alt="">
                                                                        </button>

                                                                    </form>
                                                                @elseif($gateway->method=="PayTM")
                                                                    @includeIf('paytm::paytm_btn',['type'=>'booking','amount'=> $amount])

                                                                    {{--                                                                    <form--}}
                                                                    {{--                                                                        action="{{route('upcoming_courses.prebooking.submit')}}"--}}
                                                                    {{--                                                                        method="post">--}}
                                                                    {{--                                                                        @csrf--}}
                                                                    {{--                                                                        <input type="hidden" name="method"--}}
                                                                    {{--                                                                               value="{{$gateway->method}}">--}}
                                                                    {{--                                                                        <input type="hidden" name="deposit_amount"--}}
                                                                    {{--                                                                               value="{{$amount}}">--}}
                                                                    {{--                                                                        <input type="hidden" name="course_id"--}}
                                                                    {{--                                                                               value="{{$course->id}}">--}}
                                                                    {{--                                                                        <button type="submit" class="">--}}
                                                                    {{--                                                                            <img--}}
                                                                    {{--                                                                                src="{{asset($gateway->logo)}}"--}}
                                                                    {{--                                                                                alt="">--}}
                                                                    {{--                                                                        </button>--}}

                                                                    {{--                                                                    </form>--}}

                                                                @elseif($gateway->method=="PayStack")

                                                                    <form
                                                                        action="{{route('upcoming_courses.prebooking.submit')}}"
                                                                        method="post">
                                                                        @csrf

                                                                        <input type="hidden" name="email"
                                                                               value="{{ @Auth::user()->email}}"> {{-- required --}}
                                                                        <input type="hidden" name="orderID"
                                                                               value="{{md5(uniqid(rand(), true))}}">
                                                                        <input type="hidden" name="amount"
                                                                               value="{{$amount*100}}">
                                                                        <input type="hidden" name="deposit_amount"
                                                                               value="{{$amount*100}}">

                                                                        <input type="hidden" name="currency"
                                                                               value="{{Settings('currency_code')}}">
                                                                        <input type="hidden" name="metadata"
                                                                               value="{{ json_encode($array = ['type' => 'Deposit',]) }}">
                                                                        <input type="hidden" name="reference"
                                                                               value="{{ Paystack::genTranxRef() }}"> {{-- required --}}

                                                                        <input type="hidden" name="method"
                                                                               value="{{$gateway->method}}">
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{$course->id}}">

                                                                        <button type="submit" class="">
                                                                            <img
                                                                                src="{{asset($gateway->logo)}}"
                                                                                alt="">
                                                                        </button>

                                                                    </form>
                                                                @elseif($gateway->method=="Bank Payment")
                                                                    <form class="w-100" action="" method="post">
                                                                        @csrf

                                                                        <a href="#" data-bs-toggle="modal"
                                                                           data-bs-target="#bankModel"
                                                                           class="payment_btn_text2 w-100">
                                                                            {{$gateway->method}}
                                                                        </a>
                                                                    </form>
                                                                @elseif($gateway->method == 'Authorize.Net')
                                                                    @includeIf('authorizenet::upcoming_course',['gateway' => $gateway,'amount' => $amount,'course'=>$course])
                                                                @elseif($gateway->method == 'Braintree')
                                                                    @includeIf('braintree::upcoming_course',['gateawy' => $gateway,'amount' => $amount, 'course' => $course])
                                                                @elseif($gateway->method == 'Mollie')
                                                                    @includeIf('mollie::upcoming_course',['gateawy' => $gateway,'amount' => $amount, 'course' => $course])
                                                                @elseif($gateway->method == 'Flutterwave')
                                                                    @includeIf('flutterwave::upcoming_course',['gateawy' => $gateway,'amount' => $amount, 'course' => $course])
                                                                @elseif($gateway->method == 'Coinbase')
                                                                    @includeIf('coinbase::upcoming_course',['gateawy' => $gateway,'amount' => $amount, 'course' => $course])
                                                                @elseif($gateway->method == 'Jazz Cash')
                                                                    @includeIf("jazzcash::upcoming_course",['gateawy' => $gateway,'amount' => $amount, 'course' => $course])
                                                                @elseif($gateway->method == 'CCAvenue')
                                                                    @includeIf("ccavenue::upcoming_course",['gateawy' => $gateway,'amount' => $amount, 'course' => $course])

                                                                @elseif($gateway->method == 'Wallet')
                                                                    <form class="w-100"
                                                                          action="{{ route('upcoming_courses.prebooking.submit') }}"
                                                                          method="post">

                                                                        @csrf

                                                                        <a href="#" data-bs-toggle="modal"
                                                                           data-bs-target="#MakePaymentFromCredit"
                                                                           class="payment_btn_text2 w-100">Wallet</a>
                                                                    </form>

                                                                @else

                                                                    <form
                                                                        action="{{route('upcoming_courses.prebooking.submit')}}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="method"
                                                                               value="{{$gateway->method}}">
                                                                        <input type="hidden" name="deposit_amount"
                                                                               value="{{$amount}}">
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{$course->id}}">
                                                                        <button type="submit" class="">
                                                                            <img
                                                                                src="{{asset($gateway->logo)}}"
                                                                                alt="">
                                                                        </button>

                                                                    </form>

                                                                @endif


                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="bankModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form name="bank_payment" enctype="multipart/form-data"
                      action="{{route('upcoming_courses.prebooking.submit')}} "
                      class="single_account-form" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="modal-header ps-0">
                                    <h5 class="modal-title" id="">{{__('payment.Bank Payment')}} </h5>
                                    <button type="button" class="close " data-bs-dismiss="modal">
                                        <i class="ti-close "></i>
                                    </button>

                                </div>
                                <input type="hidden" name="method" value="Bank Payment">
                                <div class="row mt-3">
                                    <div class="col-xl-6 col-md-6">
                                        <label for="name" class="mb-2">@lang('setting.Bank Name')
                                            <span class="required_mark">*</span></label>
                                        <input type="text" required class="primary_input mb_20" placeholder="Bank Name"
                                               name="bank_name" value="{{@old('bank_name')}}">
                                        <span class="invalid-feedback" role="alert" id="bank_name"></span>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <label for="name" class="mb-2">@lang('setting.Branch Name')
                                            <span class="required_mark">*</span></label>
                                        <input type="text" required name="branch_name" class="primary_input mb_20"
                                               placeholder="Name of account owner" value="{{@old('branch_name')}}">
                                        <span class="invalid-feedback" role="alert" id="owner_name"></span>
                                    </div>
                                </div>
                                <div class="row mb-20">
                                    <div class="col-xl-6 col-md-6">
                                        <label for="name" class="mb-2">@lang('setting.Account Number')
                                            <span class="required_mark">*</span></label>
                                        <input type="text" required class="primary_input mb_20"
                                               placeholder="Account number" name="account_number"
                                               value="{{@old('account_number')}}">
                                        <span class="invalid-feedback" role="alert" id="account_number"></span>
                                    </div>
                                    <div
                                        class="col-xl-6 col-md-6">
                                        <label for="name" class="mb-2">@lang('setting.Account Holder')
                                            <span class="required_mark">*</span></label>
                                        <input type="text" required name="account_holder" class="primary_input mb_20"
                                               placeholder="Account Holder" value="{{@old('account_holder')}}">
                                        <span class="invalid-feedback" role="alert" id="account_holder"></span>
                                    </div>
                                    <input type="hidden" name="deposit_amount" value="{{$amount}}">
                                    <input type="hidden" name="course_id"
                                           value="{{$course->id}}">
                                </div>
                                <div class="row mb-20">
                                    <div class="col-xl-6 col-md-12">
                                        <label for="name" class="mb-2">@lang('setting.Account Type')
                                            <span class="required_mark">*</span></label>
                                        <select class="theme_select wide update-select-arrow" name="type" required
                                                id="type" style="margin-top: -10px;">
                                            <option
                                                data-display="{{__('common.Select')}}  {{__('setting.Account Type')}}"
                                                value="">{{__('common.Select')}} {{__('setting.Account Type')}}</option>
                                            <option
                                                value="Current Account" {{(env('ACCOUNT_TYPE')? env('ACCOUNT_TYPE') : '')=='Current Account'?'selected':''}}>
                                                {{__('payment.Current Account')}}

                                            </option>
                                            <option
                                                value="Savings Account" {{(env('ACCOUNT_TYPE')? env('ACCOUNT_TYPE') : '')=='Savings Account'?'selected':''}}>
                                                {{__('payment.Savings Account')}}
                                            </option>
                                            <option
                                                value="Salary Account" {{(env('ACCOUNT_TYPE')? env('ACCOUNT_TYPE') : '')=='Salary Account'?'selected':''}}>
                                                {{__('payment.Salary Account')}}
                                            </option>
                                            <option
                                                value="Fixed Deposit" {{(env('ACCOUNT_TYPE')? env('ACCOUNT_TYPE') : '')=='Fixed Deposit'?'selected':''}}>
                                                {{__('payment.Fixed Deposit')}}
                                            </option>

                                        </select>
                                    </div>
                                    <div
                                        class="col-xl-6 col-md-12">
                                        <label for="name" class="mb-2">{{__("payment.Cheque Slip")}} <span
                                                class="required_mark">*</span></label>
                                        <input type="file" required name="image" class="primary_input mb_20">
                                        <span class="invalid-feedback" role="alert" id="amount_validation"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-header ps-0">
                                    <h5 class="modal-title"
                                        id="exampleModalLabel">{{__('common.Bank Account Info')}}</h5>
                                </div>
                                <fieldset class="mt-3">
                                    <table
                                        class="table table-bordered">

                                        <tr>
                                            <td>@lang('setting.Bank Name')</td>
                                            <td>{{getPaymentEnv('BANK_NAME')}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('setting.Branch Name')</td>
                                            <td>{{getPaymentEnv('BRANCH_NAME')}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('setting.Account Type')</td>
                                            <td>{{getPaymentEnv('ACCOUNT_TYPE')}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('setting.Account Number')</td>
                                            <td>{{getPaymentEnv('ACCOUNT_NUMBER')}}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('setting.Account Holder')</td>
                                            <td>{{getPaymentEnv('ACCOUNT_HOLDER')}}</td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class=" theme_line_btn  btn-sm  small_btn2 "
                                data-bs-dismiss="modal">@lang('common.Cancel')</button>
                        <button class="  theme_btn  btn-sm  small_btn2" type="submit">@lang('payment.Payment')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade " id="MakePaymentFromCredit"
         tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal_400px" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('student.My Account') }}</h5>
                </div>
                <form action="{{ route('upcoming_courses.prebooking.submit') }}"
                      id="infix_payment_form1" method="POST"
                      name="payment_main_balance">
                    @csrf

                    <input type="hidden" name="payment_method"
                           value="{{ $gateway->method }}">
                    <input name="method" value="Wallet"
                           id="balanceInput"
                           style="display: {{ Auth::user()->balance >=$amount ? '' : 'none' }}"
                           class="method" type="hidden">
                    <input type="hidden"
                           name="deposit_amount"
                           value="{{$amount}}">
                    <input type="hidden" name="course_id"
                           value="{{$course->id}}">


                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <label for="name"
                                       class="mb-2">{{ __('frontend.Balance') }}</label>
                                <input type="text"
                                       class="primary_input3"
                                       value="@if (Auth::user()->balance == 0) {{ Settings('currency_symbol') ?? '৳' }}0 @else{{ getPriceFormat(Auth::user()->balance) }} @endif"
                                       readonly>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <label for="name"
                                       class="mb-2">@lang('common.Purchase Price')</label>
                                <input type="text" name="amount"
                                       class="primary_input3"
                                       value="{{ getPriceFormat($amount) }}"
                                       readonly>
                            </div>
                        </div>


                    </div>
                    <div
                        class="modal-footer  d-flex justify-content-between">
                        <button type="button"
                                class="theme_line_btn  btn-sm  small_btn2"
                                data-bs-dismiss="modal">@lang('common.Cancel')</button>

                        @if (Auth::user()->balance >= $amount)
                            <button class="theme_btn  btn-sm  small_btn2"
                                    type="submit">
                                @lang('common.Pay')
                            </button>
                        @else
                            <a class="theme_line_btn  btn-sm  small_btn2"
                               href="{{ route('deposit') }}">{{ __('common.Deposit') }}</a>
                        @endif
                    </div>


                </form>
            </div>
        </div>
    </div>


    <style>
        .modal-dialog {
            max-width: 1000px;
        }
    </style>

</div>

