@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($product) ? route('products.update', $product->ProductCode) : route('products.store') }}" 
                          method="POST">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="ProductCode" class="form-label">Product Code</label>
                            <input type="text" class="form-control @error('ProductCode') is-invalid @enderror" 
                                   id="ProductCode" name="ProductCode" 
                                   value="{{ old('ProductCode', $product->ProductCode ?? '') }}"
                                   {{ isset($product) ? 'readonly' : '' }}
                                   required>
                            @error('ProductCode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control @error('ProductName') is-invalid @enderror" 
                                   id="ProductName" name="ProductName" 
                                   value="{{ old('ProductName', $product->ProductName ?? '') }}"
                                   required>
                            @error('ProductName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($product) ? 'Update Product' : 'Create Product' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 