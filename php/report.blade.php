<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-zinc-100">
<div class="mx-5 mt-4">
    <div class="mx-4 pb-1 items-center flex justify-between">
        <h3 class="text-zinc-400 text-base font-light">Report {{ $data["report_id"] }}</h3>
        <img src="{{ asset("logo.png") }}" alt="">
    </div>
    <hr class="border-b border-zinc-400 rounded-full">
    <h1 class="text-primary mt-12 ml-6 font-regular text-5xl">Carbon Calculator</h1>
    <div class="mt-7 ml-5 text-zinc-900 font-light">
        <div class="flex items-center">
            <div class="text-content-head w-[5rem] font-regular">E-mail</div>
            <div class="text-content-main">{{ $data["email"] }}</div>
        </div>
        <div class="flex items-center mt-1">
            <div class="text-content-head w-[5rem] font-regular">Date</div>
            <div class="text-content-main">{{ $data["created_at"] }}</div>
        </div>
    </div>
    <div class="grid grid-cols-5 ml-5 mt-10">
        <div class="text-content-head col-span-2 font-regular">
            Light energy consumption
        </div>
        <div class="text-content-main col-span-3">{{ $data["consumption_facility"] }} kW</div>
        <div class="text-content-head mt-5 col-span-2 font-regular">
            Lamp types
        </div>
        <div class="mt-7 col-span-3">
            <div class="grid grid-cols-5 border-b pb-2 text-center">
                <div class="col-span-3 text-content-head text-start ps-3 font-regular">Type</div>
                <div class="col-span-1 text-content-head font-regular">Wattage</div>
                <div class="col-span-1 text-content-head font-regular">Qty</div>
            </div>
            @foreach($lamps as $lamp)
                <div class="grid grid-cols-5 border-b pb-3 mt-3 text-center">
                    <div class="col-span-3 text-content-main text-start ps-3">{{ $lamp["lampType"] }}</div>
                    <div class="col-span-1 text-content-main">{{ $lamp["watt"] }}</div>
                    <div class="col-span-1 text-content-main">{{ $lamp["quantity"] }}</div>
                </div>
            @endforeach
        </div>
        <div class="text-content-head mt-5 col-span-2 font-regular">Country</div>
        <div class="text-content-main mt-5 col-span-3">{{ $data["country"] }}</div>
        <div class="text-content-head mt-5 col-span-2 font-regular">Hours per day</div>
        <div class="text-content-main mt-5 col-span-3">{{ $data["hours_per_day"] }} Hours</div>
        <div class="text-content-head mt-5 col-span-2 font-regular">Days per year</div>
        <div class="text-content-main mt-5 col-span-3">{{ $data["use_per_year"] }} days</div>
    </div>
    <hr class="border-b border-slate-400 mt-14 mb-3 rounded-full">
    <div class="flex items-center mx-7">
        <div class="w-[10rem]">
            <h3 class="text-left mb-2 text-zinc-900 font-regular text-xl">Annual carbon emissions 
            </h3>
            <div style="position: relative; width: 200px; height: 200px;">
                <svg class="CircularProgressbar " viewBox="0 0 100 100" data-test-id="CircularProgressbar">
                    <path class="CircularProgressbar-trail"
                          style="stroke: #71717A; stroke-linecap: butt; stroke-dasharray: 289.027px, 289.027px; stroke-dashoffset: 0;"
                          d="
                      M 50,50
                      m 0,-46
                      a 46,46 0 1 1 0,92
                      a 46,46 0 1 1 0,-92
                    " stroke-width="8" fill-opacity="0"></path>
                </svg>
                <div
                    style="position: absolute; width: 100%; height: 100%; margin-top: -100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <p class="font-[600] text-2xl text-slate-700">{{ $data["footprint"] }}<br></p>
                    <p class="text-sm text-center text-zinc-900"> kg of CO₂ </p>
                </div>
            </div>
        </div>
        <div class="w-[15%]">
            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" fill="currentColor"
                 class="text-primary mx-auto mt-16"
                 viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
            </svg>
        </div>
        <div class="w-[10rem]">
            <h3 class="text-left mb-2 text-primary font-regular text-xl">Carbon savings <br> potential</h3>
            <div style="position: relative; width: 200px; height: 200px;">
                <svg class="CircularProgressbar " viewBox="0 0 100 100" data-test-id="CircularProgressbar">
                    <path class="CircularProgressbar-trail"
                          style="stroke: #03B829; stroke-linecap: butt; stroke-dasharray: 289.027px, 289.027px; stroke-dashoffset: 0px;"
                          d="
                          M 50,50
                          m 0,-46
                          a 46,46 0 1 1 0,92
                          a 46,46 0 1 1 0,-92
                        " stroke-width="8" fill-opacity="0"></path>
                    <path class="CircularProgressbar-path"
                          style="stroke: #585858; stroke-linecap: butt; stroke-dasharray: 289.027px, 289.027px; stroke-dashoffset: calc({{ $data["potential"] }} * 2.89px);"
                          d="
                          M 50,50
                          m 0,-46
                          a 46,46 0 1 1 0,92
                          a 46,46 0 1 1 0,-92
                    " stroke-width="8" fill-opacity="0"></path>
                </svg>
                <div
                    style="position: absolute; width: 100%; height: 100%; margin-top: -100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <p class="text-sm text-center text-slate-600 "></p>
                    <p class="font-regular text-2xl text-[#03B829]">{{ $data["potential"] }}%<br></p>
                </div>
            </div>
        </div>
        <div class="grow w-[25%] py-2">
            <div class="mt-12">
                <div class="flex justify-start items-center">
                    <h2 class="text-3xl font-regular text-primary">{{ $data["total_euros"] }}</h2>
                    <p class="text-xl ms-2 text-zinc-900">Euros</p>
                </div>
                <p class="text-left mt-1 text-sm text-zinc-900">
                    Approximate cost for <br>
                    light upgradation
                </p>
            </div>
            <div class="mt-5">
                <div class="flex justify-start items-center">
                    <h2 class="text-3xl font-regular text-primary">{{ $data["total_months"] }}</h2>
                    <p class="text-xl ms-2 text-zinc-900">Months</p>
                </div>
                <p class="text-left mt-1 text-sm text-zinc-900">
                    ROI - return of <br>
                    investment
                </p>
            </div>
        </div>
    </div>
    <hr class="border-b border-zinc-400 my-3 rounded-full">
    <div class="grid grid-cols-3 mx-3 text-zinc-400 text-xs">
        <div class="w-1/4">
            Kaash Light Engineers
            <br>
            4la rue des romains
            <br>
            8041, Luxembourg
        </div>
        <div class="w-1/4">
            moien@kaash.eu
            <br>
            +352 691 566 820
            <br>
            www.kaash.eu
        </div>
        <div class="w-1/2">
            Registre De Commerce Et Des Sociétés - A43604
            <br>
            LU Business registration - 10133294/0
            <br>
            BGL BNP IBAN LU55 0030 1840 5176 0000
            <br>
            VAT LU34096506
        </div>
    </div>
</div>
</body>
</html>
