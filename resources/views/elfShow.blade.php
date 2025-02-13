@extends('layouts.index')
@section('content')
<div class="flex items-center justify-center pt-[1rem]">
    <button class="block lg:hidden transition hover:scale-110 duration-500 uppercase font-bold bg-candy-cane rounded-lg px-4 py-2">
        <a href="{{route('elf')}}">Back To Toy List</a>
    </button>
</div>
<div class="px-[2rem] py-[1rem] lg:py-0 lg:px-[8rem] flex flex-wrap items-center justify-center h-[613px] sm:h-[686px] lg:h-[726px] xl:h-[788px]">
    <div class="w-full bg-[#ffffff80] border border-gray-200 rounded-lg shadow">
        <h5 class="text-xl uppercase font-bold text-black border-b border-black p-4 flex justify-between items-center">
            <button class="hidden lg:block ml-14 transition hover:scale-110 duration-500 uppercase font-bold bg-candy-cane rounded-lg px-4 py-2">
                <a href="{{route('elf')}}">Back To Toy List</a>
            </button>
            <span class="lg:ml-14 text-center flex-1">{{$toy->name}}</span>
        </h5>
        <div class="flex flex-col lg:flex-row justify-between items-center p-4 pt-[1rem]">
            <img 
                class="w-[18rem] h-[16.9rem] m-4 rounded-lg shadow-lg" 
                src="{{$toy->image}}" 
                alt="Bonnie image"
            />
            <div class="flex flex-col justify-center items-center flex-grow">
                <div class="flex gap-[2rem] sm:gap-[4rem] flex-col items-center pt-4 ">
                    <span class="text-center text-xl font-medium text-black">
                        Description: <br>{{$toy->description}}
                    </span>
                    <span class="text-center text-xl font-medium text-black">
                        Age: <br>{{$toy->minimumAge}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection