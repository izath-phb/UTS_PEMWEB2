<x-layouts.app :title="__('Categories')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Update Product</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Product</flux:heading>
            <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
    <flux:badge color="lime" class="mb-3 w-full">{{session()->get('successMessage')}}</flux:badge>
    @elseif(session()->has('errorMessage'))
    <flux:badge color="red" class="mb-3 wf-full">{{session()->get('errorMessage')}}</flux:badge>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @method('patch')
        @csrf

        <flux:input label="Name" name="name" class="mb-3" value="{{ $product->name }}" />

        <flux:textarea label="Description" name="description" class="mb-3">
            {{ $product->description }}
        </flux:textarea>

        <flux:select label="Category" wire:model="industry" placeholder="Choose industry..." class="mb-3"
            name="category_id">
            @foreach($categories as $category)
            <flux:select.option value="{{ $category->id }}"
                selected="{{ $product->category_id === $category->id ? true : false }}">{{ $category->name }}
            </flux:select.option>
            @endforeach
        </flux:select>

        <flux:input label="Price" name="price" class="mb-3" value="{{ $product->price }}" />

        <flux:input label="Stock" name="stock" class="mb-3" value="{{ $product->stock }}" />

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:link href="{{ route('products.index') }}" variant="ghost" class="ml-3">Kembali</flux:link>
        </div>
    </form>
</x-layouts.app>