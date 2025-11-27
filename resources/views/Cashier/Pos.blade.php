@extends('layouts.app')

@section('content')
<div class="w-full h-full flex" style="min-height: 100vh;">
    <div class="flex-1" style="background-color: #f3f4f6;">
        <nav class="text-white p-4 shadow-lg" style="background-color: #dc2626;">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Kuya Benz</h1>
                    <p class="text-sm opacity-90">Cashier: {{ auth()->user()->username }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-white rounded-lg font-semibold" style="color: #dc2626;">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
        
        <div class="p-4">
            <!-- Categories and menu items would be loaded via JavaScript -->
            <div id="pos-container">
                <!-- This will be populated by JavaScript -->
            </div>
        </div>
    </div>
    
    <div class="w-96 bg-white shadow-2xl flex flex-col" style="height: 100vh;">
        <!-- Order summary will be here -->
        <div id="order-summary">
            <!-- Populated by JavaScript -->
        </div>
    </div>
</div>

<script>
    // JavaScript for POS functionality
    let currentOrder = [];
    let selectedCategory = 'all';

    function loadMenuItems() {
        fetch('/api/menu-items')
            .then(response => response.json())
            .then(data => {
                renderMenuItems(data);
            });
    }

    function renderMenuItems(menuItems) {
        // Implementation similar to original JS
        const container = document.getElementById('pos-container');
        // ... rest of the menu rendering logic
    }

    function addToOrder(itemId) {
        // Add item to current order
        const existingItem = currentOrder.find(item => item.id === itemId);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            currentOrder.push({
                id: itemId,
                quantity: 1,
                // ... other item properties
            });
        }
        renderOrderSummary();
    }

    function renderOrderSummary() {
        const container = document.getElementById('order-summary');
        // Render order summary similar to original JS
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', loadMenuItems);
</script>
@endsection