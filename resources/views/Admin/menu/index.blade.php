@extends('layouts.app')

@section('content')
<div class="w-full h-full flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div class="w-64 text-white shadow-2xl flex flex-col" style="background: linear-gradient(180deg, #dc2626 0%, #f59e0b 100%);">
        @include('admin.partials.sidebar')
    </div>
    
    <!-- Main Content -->
    <div class="flex-1" style="background-color: #f3f4f6;">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold" style="color: #1f2937;">Menu Management</h2>
                <button onclick="showAddMenuItemForm()" class="px-6 py-3 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition" style="background-color: #dc2626;">
                    ➕ Add Menu Item
                </button>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="space-y-3">
                    @if($menuItems->count() === 0)
                        <p class="text-gray-500 text-center py-8">No menu items yet. Add your first item!</p>
                    @else
                        @foreach($menuItems as $item)
                            <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg" style="color: #1f2937;">{{ $item->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->category }} | Stock: {{ $item->stock }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-bold text-xl" style="color: #dc2626;">₱{{ number_format($item->price, 2) }}</span>
                                    <button onclick="editMenuItem({{ $item->id }})" class="px-4 py-2 text-sm text-white rounded-lg" style="background-color: #f59e0b;">Edit</button>
                                    <form method="POST" action="{{ route('admin.menu.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700"
                                                onclick="return confirm('Are you sure you want to delete this menu item?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Menu Item Modal -->
<div id="addMenuItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold mb-6" style="color: #1f2937;">Add Menu Item</h3>
        
        <form method="POST" action="{{ route('admin.menu.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #1f2937;">Item Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #1f2937;">Category</label>
                <select name="category" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option value="">Select Category</option>
                    <option value="Chicken">Chicken</option>
                    <option value="Pork">Pork</option>
                    <option value="Beef">Beef</option>
                    <option value="Seafood">Seafood</option>
                    <option value="Soup">Soup</option>
                    <option value="Vegetables">Vegetables</option>
                    <option value="Rice">Rice</option>
                    <option value="Dessert">Dessert</option>
                    <option value="Beverage">Beverage</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #1f2937;">Price (₱)</label>
                <input type="number" name="price" step="0.01" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #1f2937;">Stock</label>
                <input type="number" name="stock" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700">
                    Add Item
                </button>
                <button type="button" onclick="hideAddMenuItemForm()" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAddMenuItemForm() {
        document.getElementById('addMenuItemModal').classList.remove('hidden');
    }
    
    function hideAddMenuItemForm() {
        document.getElementById('addMenuItemModal').classList.add('hidden');
    }
    
    function editMenuItem(itemId) {
        // You can implement edit functionality here
        alert('Edit functionality for item ID: ' + itemId);
    }
</script>
@endsection