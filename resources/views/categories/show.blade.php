@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Category Details: {{ $category->name }}</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $category->name }}</h5>
                <p class="card-text"><strong>ID:</strong> {{ $category->id }}</p>
                <p class="card-text"><strong>Created at:</strong> {{ $category->created_at->format('F d, Y H:i:s') }}</p>
                <p class="card-text"><strong>Last updated:</strong> {{ $category->updated_at->format('F d, Y H:i:s') }}</p>
                <p class="card-text"><strong>Number of Products:</strong> {{ $category->products_count }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to List</a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                </form>
            </div>
        </div>

        <h2 class="mt-4">Products in this Category</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No products in this category.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
