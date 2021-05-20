@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>
                <div class="card-body">
                    <h4>דוחות הניתנים להפקה</h4>
                    <ul>
                        <li>
                            <a href="/businesses/{{$business->id}}/reports/income">דוח הכנסות</a>
                        </li>
                        <li>
                            <a href="/businesses/{{$business->id}}/reports/VAT">דוח מע"מ</a>
                        </li>
                        <li>
                            <a href="">דוח חייבים</a>
                        </li>
                        <li>
                            <a href="">מבנה קבצים אחיד</a>
                        </li>
                        <li>
                            <a href="">צ'קים</a>
                        </li>
                    </ul>
                    <div class="row">
                            <canvas id="doughnut-chart"></canvas>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
<script>
new Chart(document.getElementById("doughnut-chart"), {
    type: 'doughnut',

    data: {
      labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
      datasets: [
        {
          label: "Population (millions)",
          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
          data: [2478,5267,734,784,433]
        }
      ]
    },
    options: {
      title: {
        display: false,
        text: 'Predicted world population (millions) in 2050'
      },

    }
});
</script>
@endsection