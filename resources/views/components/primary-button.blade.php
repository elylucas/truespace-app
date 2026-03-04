<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-ts-teal border border-transparent rounded-md font-normal text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-ts-teal focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
