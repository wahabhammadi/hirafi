@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('التحقق من البريد الإلكتروني') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p>{{ __('لقد قمنا بإرسال رمز تحقق إلى بريدك الإلكتروني.') }}</p>
                        <p>{{ __('يرجى إدخال الرمز المكون من 6 أرقام للتحقق من حسابك.') }}</p>
                    </div>

                    <form method="POST" action="{{ route('verification.verify-code') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-right">{{ __('رمز التحقق') }}</label>

                            <div class="col-md-6">
                                <input id="verification_code" type="text" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" value="{{ old('verification_code') }}" required autocomplete="off" autofocus maxlength="6">

                                @error('verification_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('تحقق') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('verification.resend') }}">
                                    {{ __('لم تستلم الرمز؟ أرسل مرة أخرى') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 