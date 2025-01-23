<form method="POST" action="{{route('logout')}}">
    @csrf
    <button type="submit" class="text-white px-4 py-2"">
        <i class="fa fa-sign-out"></i>
        Logout
    </button>
</form>