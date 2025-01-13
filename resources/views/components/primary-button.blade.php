<button  {{ $attributes->merge(['type' => 'submit', 'class' => 'disabled:bg-gray-400 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white tracking-widest hover:bg-orange-700  focus:bg-gray-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ']) }}>
    {{ $slot }}
</button>
