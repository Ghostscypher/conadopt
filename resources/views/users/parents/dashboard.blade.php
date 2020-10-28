@section('page-title', 'Parent-DB')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/parent.css')}}">
@stop

@section('content')
    <div class="nav">
        <ul>
            <li><a href="{{url('/')}}}">Home</a></li>
            <li><a href="{{url('logout')}}">Logout</a></li>
        </ul>
    </div>

    <div class="admin-content">
            @php
                $children = App\Models\Child::where('adoption_status', 0)
                    ->orWhere('adopted_by', auth()->user()->parent->id)
                    ->orderByDesc('adoption_status')
                    ->get();
            @endphp

            <div class="child-details">
                <div class="child-details-div">
                    @if ($children->count() === 0)
                        <div class="actual-child-details">
                            No children found, try again later.
                        </div>
                    @else
                        @foreach ($children as $child)
                            <div class="actual-child-details">

                                <div class="child-image">
                                    <div class="child-image-content">
                                        <img src="{{asset('children_pics/' . $child->id)}}" alt="Child pic">
                                    </div>
                                </div>

                                <div class="actual-child-detail-content">
                                    <div class="child-name">{{$child->name}}</div>
                                    <div class="child-gender">{{$child->gender}}</div>
                                    <div class="child-age">{{$child->DOB}}</div>
                                    <div class="child-place-of-birth">{{$child->place_of_birth}}</div>

                                    @if((int) $child->adoption_status === 1 && $child->parent !== null)
                                        <div class="child-adopted-by" style="font-weight: bold">Adoption request sent</a></div>
                                    @elseif((int) $child->adoption_status === 2 && $child->parent !== null)
                                        <div class="child-adopted-by" style="font-weight: bold">Adoption request rejected</a></div>
                                    @elseif((int) $child->adoption_status === 3 && $child->parent !== null)
                                        <div class="child-adopted-by" style="font-weight: bold">Adoption request accepted</a></div>
                                    @endif

                                    <div id="error-id" class="error"></div>

                                    <div class="edit-buttons">
                                        @if($child->parent === null || (int) $child->adoption_status === 2)
                                            <button class="btn-adopt" data-target="{{$child->id}}">Adopt</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

@endsection

@include('layout.body')

<script>
    if(qs('.btn-adopt') !== null){
        qs('.btn-adopt').onclick = function(){
            qsa('.error').forEach(element => {
                element.innerHTML = '';
            });

            ajaxPOST(`${location.origin}/child/adopt`, JSON.stringify({
                id: this.dataset.target,
            }), null, onChildAdoptedCallback);
        };
    };

    function onChildAdoptedCallback(data){
        if(data.code === 200){
            window.location.href = '';
        } else if(data.code === 400) {
            let messages = data.response.message;

            for (const message in messages) {
                qs(`#error-${message}`).innerHTML = messages[message];
            }

        }else if(result.code === 429){
            qs('#error-id').innerHTML = "Too many attempts please try again later";
        } else if(result.code === 419) {
            // Invalid csrf token refresh the page
            window.location.href = 'login';
        } else {
            qs('#error-id').innerHTML = 'An unknown error occured, refresh your page and try again';
        }
    }

</script>
