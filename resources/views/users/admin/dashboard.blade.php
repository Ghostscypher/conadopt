@section('page-title', 'Admin-DB')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/admin.css')}}">
@stop

@section('content')
    <div class="report-popup" style="display: none">
        <div class="report-popup-div">
            <div class="table-title">Child adoption reports</div>
            <div class="report-filters">
                <select name="filter" id="filter">
                    <option class="option-class" value="all" selected>All</option class="option-class">
                    <option class="option-class" value="thisday">This day</option>
                    <option class="option-class" value="thismonth">This month</option>
                </select>
            </div>
            <div id="report-table" class="report-table">
                <table>
                    Please wait fetching data....
                </table>
            </div>

            <div class="save-changes-buttons">
                <button id="btn-report_popup_cancel">Cancel</button>
                <button id="btn-report_popup_download">Download</button>
            </div>
        </div>
    </div>

    <div class="popup" style="display: none">
        <div class="popup-container">
            <div class="popup-div">
                <div class="name">
                    <label for="name">Name</label>
                    <input type="text" id="name" placeholder="Enter name">
                </div>
                <div id="error-name" class="error"></div>

                <div class="age">
                    <label for="dob">Date of birth</label>
                    <input type="text" id="dob" placeholder="Enter date of birth">
                </div>
                <div id="error-dob" class="error"></div>

                <div class="age">
                    <label for="place_of_birth">Place of birth</label>
                    <input type="text" id="place_of_birth" placeholder="Enter birth place">
                </div>
                <div id="error-place_of_birth" class="error"></div>

                <div class="age">
                    <label for="birthplace">Child photo</label>
                    <input type="file" id="photo" placeholder="Upload child photo">
                </div>
                <div id="error-photo" class="error"></div>

                <div class="gender">
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="male" checked>
                    <label for="female">Female</label>
                    <input type="radio" name="gender" id="female">
                </div>
                <div id="error-gender" class="error"></div>

                <div class="save-changes-buttons">
                    <button id="btn-child_popup_cancel">Cancel</button>
                    <button id="btn-child_popup_save" data-mode="save" data-cid="">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="nav">
        <ul>
            <li><a href="{{url('/')}}">Home</a></li>
            <li><a href="{{url('logout')}}">Logout</a></li>
            <li id="btn-reports">Reports</li>
            <li id="btn-add_child">Add child</li>
        </ul>
    </div>

    @php
        $children = App\Models\Child::orderByDesc('adoption_status')->get();
    @endphp

    <script>
        let children = @json($children);
        let children_map = new Map();

        for (const child of children) {
            children_map.set(child.id, child);
        }
    </script>

    <div class="child-details">
        <div class="child-details-div">
            @if ($children->count() === 0)
                <div class="actual-child-details">
                    No children found, please add at least one child.
                </div>
            @else
                @foreach ($children as $child)
                    <div class="actual-child-details">

                        <div class="child-image">
                            <div class="child-image-content">
                                <img src="{{asset('children_pics/' . $child->id)}}" alt="Girl potrait">
                            </div>
                        </div>

                        <div class="actual-child-detail-content">
                            <div class="child-name">{{$child->name}}</div>
                            <div class="child-gender">{{$child->gender}}</div>
                            <div class="child-age">{{$child->DOB}}</div>
                            <div class="child-place-of-birth">{{$child->place_of_birth}}</div>

                            @if($child->parent !== null)
                                @if((int) $child->adoption_status === 1)
                                    <div class="child-adopted-by" style="font-weight: bold">Adoption request by <a href="#">{{$child->parent->user->name}}</a></div>
                                @else
                                    <div class="child-adopted-by">Adopted by <a href="#">{{$child->parent->user->name}}</a></div>
                               @endif
                            @else
                            <div class="child-adopted-by">No adoption requests</a></div>
                            @endif

                            <div class="edit-buttons">
                                <button class="btn-edit_child" data-target="{{$child->id}}">Edit</button>
                                {{-- <button class="btn-delete_child" data-target="{{$child->id}}">Delete</button> --}}
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
    qs('#btn-reports').onclick = function(e){
        qs('.report-popup').style.display = 'block';

        ajaxGet(`${location.origin}/child/reports/get/${qs('#filter').value.trim().toLowerCase()}`, null, null, onReportsFetchedCallback);
    };

    qs('#btn-add_child').onclick = function(e){
        qs('#btn-child_popup_save').dataset.mode = 'save';
        qs('.popup').style.display = 'block';
        qs('#btn-child_popup_save').dataset.cid = "";
        qs('#name').value = '';
        qs('#dob').value = '';
        qs('#place_of_birth').value = '';
    };

    qs('#btn-report_popup_cancel').onclick = function(){
        qs('.report-popup').style.display = 'none';
    };

    qs('#btn-report_popup_download').onclick = function(){
        printJS({
            printable: 'report-table',
            type: 'html',
            css: "{{asset('assets/css/admin.css')}}",
        });
    };

    qs('#btn-child_popup_save').onclick = function(){
        qsa('.error').forEach(element => {
            element.innerHTML = '';
        });

        form_data = new FormData();
        form_data.set('id', qs('#btn-child_popup_save').dataset.cid === undefined ? '' : qs('#btn-child_popup_save').dataset.cid);
        form_data.set('photo', qs('#photo').files[0] === undefined ?  '' : qs('#photo').files[0]);
        form_data.set('name', qs('#name').value);
        form_data.set('dob', qs('#dob').value);
        form_data.set('place_of_birth', qs('#place_of_birth').value);
        form_data.set('gender', qs('#male').getAttribute('checked') === null ? false : true);

        switch (qs('#btn-child_popup_save').dataset.mode) {
            case 'save':
                ajaxPOST(`${location.origin}/child/save`, form_data, null, onSaveChildDetailsCallback, true);
                break;

            case 'edit':
                ajaxPOST(`${location.origin}/child/edit`, form_data, null, onEditChildDetailsCallback, true);
                break;

            default:
                break;
        }
    };

    qs('#btn-child_popup_cancel').onclick = function(){
        qs('.popup').style.display = 'none';
    };

    function onSaveChildDetailsCallback(data){
        if(data.code === 200){
            window.location.href = '';
        } else if(data.code === 400) {
            let messages = data.response.message;

            for (const message in messages) {
                qs(`#error-${message}`).innerHTML = messages[message];
            }

        }else if(result.code === 429){
            qs('#error-gender').innerHTML = "Too many attempts please try again later";
        } else if(result.code === 419) {
            // Invalid csrf token refresh the page
            window.location.href = 'login';
        } else {
            qs('#error-gender').innerHTML = 'An unknown error occured, refresh your page and try again';
        }
    }

    function onEditChildDetailsCallback(data){
        if(data.code === 200){
            window.location.href = '';
        } else if(data.code === 400) {
            let messages = data.response.message;

            for (const message in messages) {
                qs(`#error-${message}`).innerHTML = messages[message];
            }

        }else if(result.code === 429){
            qs('#error-gender').innerHTML = "Too many attempts please try again later";
        } else if(result.code === 419) {
            // Invalid csrf token refresh the page
            window.location.href = 'login';
        } else {
            qs('#error-gender').innerHTML = 'An unknown error occured, refresh your page and try again';
        }
    }

    function onReportsFetchedCallback(data){

        if(data.code === 200){
            let value =  `<table>
                    <tr>
                        <th>Name</th>
                        <th>D.O.B</th>
                        <th>P.O.B</th>
                        <th>Adopted On</th>
                        <th>Adopted BY</th>
                    </tr>
                `;

            for (const record of data.response.message) {
                let DOB = new Date(record.DOB);
                value += `<tr>
                            <td>${record.name}</td>
                            <td>${DOB.toDateString()}</td>
                            <td>${record.place_of_birth}</td>
                            <td>${record.adopted_on}</td>
                            <td>${record.parent.user.name}</td>
                        </tr>`;
            }

            qs('#report-table').innerHTML = value;

        } else {

        }

    }

    qs('.btn-edit_child').onclick = function(){
        qs('#btn-child_popup_save').dataset.mode = 'edit';
        qs('.popup').style.display = 'block';
        qs('#btn-child_popup_save').dataset.cid = this.dataset.target;


        let child = children_map.get(parseInt(this.dataset.target));

        qs('#name').value = child.name;
        qs('#dob').value = child.DOB;
        qs('#place_of_birth').value = child.place_of_birth;

        if(child.gender === 'male'){
            qs('#male').setAttribute('checked', true);
        } else {
            qs('#female').setAttribute('checked', true);
        }

    };

    let timer = null;

    qs('#filter').onchange = function(){
        if(timer !== null){
            clearInterval(timer);
        }

        setTimeout(() => {
            qs('#report-table').innerHTML = `<table>Please waiy fething data</table>`;
            ajaxGet(`${location.origin}/child/reports/get/${this.value.trim().toLowerCase()}`, null, null, onReportsFetchedCallback);
        }, 2000);

    };

    // qs('.btn-delete_child').onclick = function(){
    // };

</script>
