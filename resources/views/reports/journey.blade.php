<html>

<head>
    <title>Customer Journey</title>
    <link rel="stylesheet" href="/css/bootstrap.css">

    <style>

    #tableStage th{
        background: black;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td>
                    <img src="/images/logo.jpg" width="80pt"/>
                </td>
                <td class="text-center">
                    <h1 class="text-center" style="font-size:30pt; font-weight: bold;">NEO Agency Advertising</h1>
                    <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">Customer Journey</h2>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table width="100%">
            <tr>
                <td width="8%">Konsumen</td>
                <td width="42%">: {{ $data->user->nama }}</td>
                <td width="8%">Goal</td>
                <td width="42%">{{ $data->goal }}</td>
            </tr>
            <tr>
                <td>No Pesanan</td>
                <td>: {{ $data->order->nomor }}</td>
            </tr>
        </table>
        <hr/>
        <table class="table table-bordered w-100" id="tableStage">
            <tbody>
                <tr id="stageRow">
                    <th width="100px">Stage</th>
                    @foreach($stage as $d)
                        <th>{{ $d->stage }}</th>
                    @endforeach
                </tr>
                <tr id="experienceRow">
                    <th width="100px">Experiences</th>
                    @foreach($stage as $d)
                        <td>{{ $d->experience }}</td>
                    @endforeach
                </tr>
                <tr id="opportunitiesRow">
                    <th width="100px">Opportunities</th>
                    @foreach($stage as $d)
                        <td>{{ $d->opportunities }}</td>
                    @endforeach
                </tr>
                <tr id="expectationRow">
                    <th width="100px">Expectations</th>
                    @foreach($stage as $d)
                        <td>{{ $d->expectation }}</td>
                    @endforeach
                </tr>
                <tr id="feelingsRow">
                    <th width="100px">Feelings</th>
                    @foreach($stage as $d)
                        <td>{{ $d->feeling }}</td>
                    @endforeach
                </tr>
                <tr id="touchPointRow">
                    <th width="100px">Touch Point</th>
                    @foreach($stage as $d)
                        <td>{{ $d->touch_point }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>