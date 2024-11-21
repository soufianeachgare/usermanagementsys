@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">POS System</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product List -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Products</h2>
                <input type="text" id="product-search" placeholder="Search products..."
                    class="w-full p-2 mb-4 border rounded">
                <div class="mb-4">
                    <label for="category-filter" class="block text-sm font-medium text-gray-700">Filter by
                        Category:</label>
                    <select id="category-filter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="product-list" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <!-- Products will be dynamically inserted here -->
                </div>
            </div>

            <!-- Cart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Cart</h2>
                <div id="cart-items" class="mb-4">
                    <!-- Cart items will be dynamically inserted here -->
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold">Total: $<span id="cart-total">0.00</span></span>
                    <button id="checkout-btn"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Product data from the server
        const products = @json($products);
        const categories = @json($categories);

        let cart = [];

        // DOM elements
        const productList = document.getElementById('product-list');
        const cartItems = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');
        const checkoutBtn = document.getElementById('checkout-btn');
        const productSearch = document.getElementById('product-search');
        const categoryFilter = document.getElementById('category-filter');

        // Render product list
        function renderProducts(products) {
            productList.innerHTML = products.map(product => `
                <button onclick="addToCart(${product.id})" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 flex flex-col items-center justify-center">
                    <span class="font-semibold">${product.name}</span>
                    <span>$${product.price.toFixed(2)}</span>
                    <span class="text-xs">Stock: ${product.stock}</span>
                </button>
            `).join('');
        }

        // Render cart
        function renderCart() {
            cartItems.innerHTML = cart.map(item => `
                <div class="flex justify-between items-center mb-2">
                    <span>${item.name} - $${item.price.toFixed(2)} x ${item.quantity}</span>
                    <div>
                        <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" class="bg-gray-300 text-gray-700 px-2 py-1 rounded hover:bg-gray-400">-</button>
                        <span class="mx-2">${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" class="bg-gray-300 text-gray-700 px-2 py-1 rounded hover:bg-gray-400">+</button>
                        <button onclick="removeFromCart(${item.id})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-2">Remove</button>
                    </div>
                </div>
            `).join('');

            const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            cartTotal.textContent = total.toFixed(2);
        }

        // Add item to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (product.stock <= 0) {
                alert('This product is out of stock.');
                return;
            }
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity += 1;
                } else {
                    alert('Cannot add more of this item. Stock limit reached.');
                    return;
                }
            } else {
                cart.push({
                    ...product,
                    quantity: 1
                });
            }

            renderCart();
        }

        // Update item quantity in cart
        function updateQuantity(productId, newQuantity) {
            const item = cart.find(item => item.id === productId);
            const product = products.find(p => p.id === productId);

            if (newQuantity > 0 && newQuantity <= product.stock) {
                item.quantity = newQuantity;
            } else if (newQuantity > product.stock) {
                alert('Cannot add more of this item. Stock limit reached.');
            } else {
                removeFromCart(productId);
            }

            renderCart();
        }

        // Remove item from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            renderCart();
        }

        // Checkout
        checkoutBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            // Send cart data to the server
            fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: cart,
                        total: parseFloat(cartTotal.textContent)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Order created successfully') {
                        alert('Order placed successfully!');
                        cart = [];
                        renderCart();
                    } else {
                        alert('Error creating order. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        });

        // Search products
        productSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const categoryId = categoryFilter.value;
            filterProducts(searchTerm, categoryId);
        });

        // Filter products by category
        categoryFilter.addEventListener('change', (e) => {
            const categoryId = e.target.value;
            const searchTerm = productSearch.value.toLowerCase();
            filterProducts(searchTerm, categoryId);
        });

        function filterProducts(searchTerm, categoryId) {
            const filteredProducts = products.filter(product =>
                product.name.toLowerCase().includes(searchTerm) &&
                (categoryId === '' || product.category_id.toString() === categoryId)
            );
            renderProducts(filteredProducts);
        }

        // Initial render
        renderProducts(products);
        renderCart();
    </script>
@endsection
