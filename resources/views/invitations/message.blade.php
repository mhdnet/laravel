<x-guest-layout>
    <div class="flex flex-col items-center justify-center">
        <div class=" border-2 border-green-800 rounded-full p-5">
            <svg class="w-10 fill-none stroke-green-800"
                 stroke-width="4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
{{--                <path stroke-linecap="round"--}}
{{--                      stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"--}}

{{--                      stroke-opacity="0.2"--}}
{{--                      stroke="#000"--}}
{{--                      transform="translate(0.5 1.5)"/>--}}
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>


        <div class="font-bold text-md mt-4">تم قبول الدعوة بجاح!</div>
    </div>
    <div class="my-3">
        لأدارة الحساب
        <br>
        يمكنك الأنتقال الى <a href="/" class="text-blue-500 font-bold">تطبيق الوب</a>

        او التحميل من المتجر
    </div>

    <x-google-play class="mx-20 my-10" />

    <x-app-store class="mx-20 my-10" />

    <a class="font-bold text-blue-500" href="/">الرئيسية</a>


{{--    <div class=""> {!! nl2br($message) !!} </div>--}}
</x-guest-layout>
