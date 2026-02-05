# 🎨 BLADE COMPONENTS - Ready to Implement

## 📦 Components to Create

Saya akan membuat 5 reusable Blade components yang langsung bisa Anda gunakan.

---

## Component #1: Mobil Image

**File:** `resources/views/components/mobil-image.blade.php`

```blade
@props([
    'mobil',
    'size' => 'medium',
    'class' => '',
    'showPlaceholder' => true
])

@php
    $sizeClasses = match($size) {
        'small' => 'h-12 w-16',
        'medium' => 'h-20 w-32',
        'large' => 'h-40 w-full',
        'xlarge' => 'h-96 w-full',
        default => 'h-20 w-32',
    };
@endphp

<div>
    @if($mobil->gambar && $showPlaceholder)
        <img 
            src="{{ $mobil->gambar }}" 
            alt="{{ $mobil->merk }} {{ $mobil->model }}"
            class="{{ $sizeClasses }} rounded-lg object-cover {{ $class }}"
            loading="lazy"
        >
    @else
        <div class="{{ $sizeClasses }} rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center border border-blue-300 {{ $class }}">
            <span class="text-white font-bold" role="img" aria-label="Car placeholder">
                @switch($size)
                    @case('small')
                        🚗
                        @break
                    @case('large')
                        <span class="text-7xl">🚗</span>
                        @break
                    @case('xlarge')
                        <span class="text-8xl">🚗</span>
                        @break
                    @default
                        🚗
                @endswitch
            </span>
        </div>
    @endif
</div>
```

**Usage Examples:**

```blade
<!-- Small version -->
<x-mobil-image :mobil="$mobil" size="small" />

<!-- Medium version (default) -->
<x-mobil-image :mobil="$mobil" />

<!-- Large version -->
<x-mobil-image :mobil="$mobil" size="large" />

<!-- Extra large version -->
<x-mobil-image :mobil="$mobil" size="xlarge" />

<!-- With custom classes -->
<x-mobil-image :mobil="$mobil" class="shadow-lg border-2 border-gray-300" />

<!-- Combined -->
<x-mobil-image 
    :mobil="$mobil" 
    size="large" 
    class="shadow-md border border-gray-300"
/>
```

---

## Component #2: Price Display

**File:** `resources/views/components/price-display.blade.php`

```blade
@props([
    'amount' => 0,
    'label' => null,
    'size' => 'default',
    'withIcon' => false,
    'format' => 'full' // 'full' or 'short'
])

@php
    $sizeClasses = match($size) {
        'small' => 'text-sm',
        'large' => 'text-3xl',
        'xlarge' => 'text-4xl',
        default => 'text-2xl',
    };
    
    $formattedPrice = 'Rp ' . number_format($amount, 0, ',', '.');
@endphp

<div {{ $attributes }}>
    @if($label)
        <p class="text-gray-600 text-sm mb-1">{{ $label }}</p>
    @endif
    
    <p class="font-bold text-gray-800 {{ $sizeClasses }}">
        @if($withIcon)
            💰 
        @endif
        {{ $formattedPrice }}
    </p>
</div>
```

**Usage:**

```blade
<!-- Simple price -->
<x-price-display :amount="$mobil->harga_baru" />

<!-- With label -->
<x-price-display 
    :amount="$mobil->harga_baru" 
    label="Harga Baru"
/>

<!-- Large with icon -->
<x-price-display 
    :amount="$mobil->harga_baru" 
    label="Harga Baru"
    size="large"
    withIcon
/>

<!-- Score display (use default format) -->
<x-price-display 
    :amount="$result['score']" 
    label="Skor MABAC"
    size="xlarge"
/>
```

---

## Component #3: Type Badge

**File:** `resources/views/components/type-badge.blade.php`

```blade
@props([
    'type' => 'benefit',
    'showLabel' => true,
    'variant' => 'default' // 'default' or 'compact'
])

@php
    $badgeClasses = match($type) {
        'benefit' => 'bg-green-100 text-green-800',
        'cost' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800',
    };
    
    $label = $type === 'benefit' ? 'Benefit' : 'Cost';
    $description = $type === 'benefit' 
        ? 'Semakin tinggi semakin baik'
        : 'Semakin rendah semakin baik';
    $icon = $type === 'benefit' ? '📈' : '📉';
@endphp

@if($variant === 'compact')
    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClasses }}">
        {{ $icon }} {{ $label }}
    </span>
@else
    <div class="inline-block">
        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClasses }}">
            {{ $icon }} {{ $label }}
        </span>
        @if($showLabel)
            <p class="text-xs text-gray-600 mt-1">{{ $description }}</p>
        @endif
    </div>
@endif
```

**Usage:**

```blade
<!-- Compact badge -->
<x-type-badge type="benefit" variant="compact" />

<!-- Full badge with description -->
<x-type-badge type="cost" />

<!-- In table -->
<td>
    <x-type-badge :type="$kriteria->tipe" variant="compact" />
</td>

<!-- In list -->
<div class="space-y-2">
    @foreach($kriterias as $kriteria)
        <div class="flex justify-between">
            <span>{{ $kriteria->nama }}</span>
            <x-type-badge :type="$kriteria->tipe" variant="compact" />
        </div>
    @endforeach
</div>
```

---

## Component #4: Ranking Badge

**File:** `resources/views/components/ranking-badge.blade.php`

```blade
@props([
    'rank' => 1,
    'size' => 'default', // 'small', 'default', 'large'
    'showLabel' => false
])

@php
    $sizeClasses = match($size) {
        'small' => 'w-6 h-6 text-xs',
        'large' => 'w-12 h-12 text-lg',
        default => 'w-8 h-8 text-base',
    };
    
    $medals = [
        1 => '🥇',
        2 => '🥈',
        3 => '🥉',
    ];
    
    $medal = $medals[$rank] ?? null;
    $label = match($rank) {
        1 => 'Juara 1',
        2 => 'Juara 2',
        3 => 'Juara 3',
        default => 'Ranking ' . $rank,
    };
@endphp

<div class="inline-flex flex-col items-center">
    @if($medal)
        <span class="text-4xl">{{ $medal }}</span>
    @else
        <div class="inline-flex items-center justify-center {{ $sizeClasses }} bg-blue-600 text-white rounded-full font-bold border-2 border-blue-700">
            {{ $rank }}
        </div>
    @endif
    
    @if($showLabel)
        <span class="text-xs text-gray-600 mt-1">{{ $label }}</span>
    @endif
</div>
```

**Usage:**

```blade
<!-- Medal for top 3 -->
<x-ranking-badge :rank="1" />
<x-ranking-badge :rank="2" />
<x-ranking-badge :rank="3" />

<!-- Number badge for others -->
<x-ranking-badge :rank="4" />
<x-ranking-badge :rank="5" />

<!-- With labels -->
<x-ranking-badge :rank="1" showLabel />

<!-- Large size -->
<x-ranking-badge :rank="1" size="large" showLabel />

<!-- In table -->
<td>
    <x-ranking-badge :rank="$result['rank']" />
</td>
```

---

## Component #5: Mobil Card

**File:** `resources/views/components/mobil-card.blade.php`

```blade
@props([
    'mobil',
    'clickable' => true,
    'route' => null,
    'showPrice' => true,
    'showFuelEfficiency' => true,
    'showSecurity' => true,
])

@php
    if (!$route && $clickable) {
        $route = route('mobil.show', $mobil);
    }
@endphp

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition {{ $clickable ? 'cursor-pointer' : '' }}">
    <!-- Image -->
    <x-mobil-image :mobil="$mobil" size="medium" class="w-full h-48" />
    
    <div class="p-6">
        <!-- Header -->
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            {{ $mobil->merk }} {{ $mobil->model }}
        </h2>
        <p class="text-gray-600 mb-4">
            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">
                {{ $mobil->tipe }}
            </span>
            <span class="ml-2 text-gray-500">{{ $mobil->tahun }}</span>
        </p>

        <!-- Details -->
        <div class="space-y-2 mb-4 text-sm text-gray-600">
            @if($showPrice)
                <p><strong>Harga Baru:</strong> <x-price-display :amount="$mobil->harga_baru" /></p>
            @endif
            
            @if($showFuelEfficiency)
                <p><strong>Jarak Tempuh:</strong> {{ $mobil->jarak_tempuh }} km/l</p>
            @endif
            
            @if($showSecurity)
                <p><strong>Fitur Keamanan:</strong> {{ $mobil->fitur_keamanan }}</p>
            @endif
        </div>

        <!-- Action -->
        @if($clickable && $route)
            <a href="{{ $route }}" class="block bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition">
                Lihat Detail
            </a>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
```

**Usage:**

```blade
<!-- Simple card with default settings -->
<x-mobil-card :mobil="$mobil" />

<!-- Card without price -->
<x-mobil-card :mobil="$mobil" :showPrice="false" />

<!-- Grid of cards -->
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($mobils as $mobil)
        <x-mobil-card :mobil="$mobil" />
    @endforeach
</div>

<!-- Non-clickable card with custom slot -->
<x-mobil-card :mobil="$mobil" :clickable="false">
    <button class="w-full bg-green-600 text-white py-2 rounded">
        Pilih untuk Perhitungan
    </button>
</x-mobil-card>
```

---

## Helper Functions

### File: `app/Helpers/FormatHelper.php`

```php
<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format amount as Indonesian currency
     */
    public static function price($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format price without currency symbol
     */
    public static function priceOnly($amount): string
    {
        return number_format($amount, 0, ',', '.');
    }

    /**
     * Format decimal number
     */
    public static function decimal($value, $places = 2): string
    {
        return number_format($value, $places, ',', '.');
    }

    /**
     * Format fuel efficiency (km/l)
     */
    public static function fuelEfficiency($value): string
    {
        return $value . ' km/l';
    }

    /**
     * Format engine capacity (cc)
     */
    public static function engineCapacity($value): string
    {
        return $value . ' cc';
    }
}
```

### File: `app/Helpers/CriteriaHelper.php`

```php
<?php

namespace App\Helpers;

class CriteriaHelper
{
    /**
     * Get criteria type label
     */
    public static function typeLabel(string $type): string
    {
        return $type === 'benefit'
            ? 'Semakin tinggi semakin baik'
            : 'Semakin rendah semakin baik';
    }

    /**
     * Get short type label
     */
    public static function typeLabelShort(string $type): string
    {
        return $type === 'benefit' ? 'Benefit' : 'Cost';
    }

    /**
     * Get type color
     */
    public static function typeColor(string $type): string
    {
        return $type === 'benefit' ? 'green' : 'red';
    }

    /**
     * Get badge CSS classes for type
     */
    public static function typeBadgeClass(string $type): string
    {
        return $type === 'benefit'
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    /**
     * Get type icon
     */
    public static function typeIcon(string $type): string
    {
        return $type === 'benefit' ? '📈' : '📉';
    }
}
```

---

## How to Register Helpers

### Step 1: Create Helper Files
- `app/Helpers/FormatHelper.php`
- `app/Helpers/CriteriaHelper.php`

### Step 2: Register in composer.json

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/Helpers/FormatHelper.php",
            "app/Helpers/CriteriaHelper.php"
        ]
    }
}
```

### Step 3: Run composer autoload

```bash
composer dump-autoload
```

### Step 4: Use in Blade

```blade
{{ FormatHelper::price($mobil->harga_baru) }}
{{ CriteriaHelper::typeLabel($kriteria->tipe) }}
```

---

## Usage Examples

### Before (Old Way - With Duplication):

```blade
<!-- perhitungan/index.blade.php -->
@foreach($mobils as $mobil)
    <label>
        @if($mobil->gambar)
            <img src="{{ $mobil->gambar }}" ...>
        @else
            <div>🚗</div>
        @endif
        {{ $mobil->merk }} {{ $mobil->model }}
        @if($kriteria->tipe === 'benefit')
            Semakin tinggi semakin baik
        @else
            Semakin rendah semakin baik
        @endif
    </label>
@endforeach

<!-- mobil/index.blade.php -->
@foreach($mobils as $mobil)
    <div>
        @if($mobil->gambar)
            <img src="{{ $mobil->gambar }}" ...>
        @else
            <div>🚗</div>
        @endif
        Rp {{ number_format($mobil->harga_baru, 0, ',', '.') }}
    </div>
@endforeach
```

### After (New Way - With Components & Helpers):

```blade
<!-- perhitungan/index.blade.php -->
@foreach($mobils as $mobil)
    <label>
        <x-mobil-image :mobil="$mobil" size="small" />
        {{ $mobil->merk }} {{ $mobil->model }}
        <x-type-badge :type="$kriteria->tipe" variant="compact" />
    </label>
@endforeach

<!-- mobil/index.blade.php -->
@foreach($mobils as $mobil)
    <x-mobil-card :mobil="$mobil" />
@endforeach
```

---

## Benefits Summary

| Aspek | Before | After |
|-------|--------|-------|
| **Code Duplication** | 4x (image logic) | 0x (centralized) |
| **Maintainability** | Hard | Easy |
| **Reusability** | No | Yes |
| **Lines of Code** | More | Less |
| **Consistency** | Manual | Automatic |
| **Testing** | Harder | Easier |

---

**Components & Helpers ready to implement! Copy them directly to your project.** 🎨

