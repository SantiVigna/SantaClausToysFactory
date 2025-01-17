@extends('layouts.index')
@section('content')
<div class="flex items-center justify-center pt-[1rem]">
    <button class="block sm:hidden transition hover:scale-110 duration-500 uppercase font-bold bg-candy-cane rounded-lg px-4 py-2">
        <a href="{{route('santa')}}">Back To Kid List</a>
    </button>
</div>
<div class="px-[2rem] py-[1rem] sm:py-0 sm:px-[8rem] flex flex-wrap items-center justify-center lg:h-[445px] xl:h-[813px]">
    <div class="w-full bg-[#ffffff80] border border-gray-200 rounded-lg shadow xl:h-[500px]">
        <h5 class="text-xl uppercase font-bold text-black border-b border-black p-4 flex justify-between items-center">
            <button class="hidden sm:block ml-14 transition hover:scale-110 duration-500 uppercase font-bold bg-candy-cane rounded-lg px-4 py-2">
                <a href="{{route('santa')}}">Back To Kid List</a>
            </button>
            <span class="sm:ml-14 text-center flex-1">{{$kid->name}} {{$kid->surname}}</span>
        </h5>
        <div class="flex flex-col sm:flex-row justify-between items-center p-4 xl:pt-[4rem]">
            <img 
                class="w-[18rem] h-[16.9rem] m-2 rounded-lg shadow-lg object-cover" 
                src="{{$kid->image}}" 
                alt="Kid Image"
            />
            <div class="flex flex-col justify-center items-center flex-grow">
                <div class="flex gap-[2rem] flex-col items-center pt-4 ">
                    <span class="text-center text-xl font-medium text-black">
                        Age: {{$kid->age}} years
                    </span>
                    <span class="text-center text-xl font-medium text-black">
                        Gender: {{$kid->gender->name}}
                    </span>
                    <span class=" text-center text-xl font-medium text-black">
                        Country: {{$kid->country->name}}
                    </span>
                    <span class="text-center text-xl font-medium text-black">
                        Attitude: {{$kid->attitude}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection