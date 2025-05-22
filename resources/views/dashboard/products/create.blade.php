<x-layouts.app :title="__('Products')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Add New Product</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Product</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
    <flux:badge color="lime" class="mb-3 w-full">{{ session('successMessage') }}</flux:badge>
    @elseif(session()->has('errorMessage'))
    <flux:badge color="red" class="mb-3 w-full">{{ session('errorMessage') }}</flux:badge>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <flux:input label="Name" name="name" class="mb-3" :value="old('name')" />

        <flux:textarea label="Description" name="description" class="mb-3">
            {{ old('description') }}
        </flux:textarea>

        <flux:input label="Slug" name="slug" class="mb-3" :value="old('slug')" />

        <flux:input label="SKU" name="sku" class="mb-3" :value="old('sku')" />

        <flux:input type="file" label="Image" name="image" class="mb-3" />

        <flux:select label="Category" name="category_id" placeholder="Choose category..." class="mb-3">
            @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>
                {{ $category->name }}
            </option>
            @endforeach
        </flux:select>

        <flux:input label="Price" name="price" class="mb-3" type="number" :value="old('price')" />

        <flux:input label="Stock" name="stock" class="mb-3" type="number" :value="old('stock')" />

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Simpan</flux:button>
            <flux:link href="{{ route('products.index') }}" variant="ghost" class="ml-3">Kembali</flux:link>
        </div>
    </form>
</x-layouts.app>