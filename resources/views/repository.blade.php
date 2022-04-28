<x-layout.template.base title="Cascading Comments">

    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    @endpush

    <div class="flex items-center mb-4">
        <img src="{{ $repository->logo_url }}" alt="Logo for the {{ $repository->display_name }} repository" class="h-12" height="48">

        <x-h1 class="ml-4">Cascading comments of {{ $repository->display_name }}</x-h1>
    </div>

    <div>
        <canvas id="chart"></canvas>
    </div>


    <div class="flex justify-between items-center mt-4">
        <x-h2>Cascading comments</x-h2>

        <x-inputs.select label="Release"
                         name="release"
                         :value="$release->id"
                         :options="$releaseOptions"
                         x-data="{}"
                         @change="Livewire.emit('setRelease', $event.target.value)"
        />
    </div>

    <livewire:comments-in-release :release="$release"/>



    <script>
        new Chart(
            document.getElementById('chart'),
            {
                type: 'line',
                data: {
                    labels: @js($labels),
                    datasets: [
                        {
                            label: 'Perfect %',
                            backgroundColor: 'rgb(255,206,13)',
                            borderColor: 'rgb(255,206,13)',
                            data: @js($perfectPercentage),
                            yAxisID: 'y1',
                        },
                        {
                            label: 'Imperfect comments',
                            backgroundColor: 'rgb(255, 99, 132)',
                            fill: true,
                            borderColor: 'rgb(255, 99, 132)',
                            data: @js($imperfectCommentsData),
                            yAxisID: 'y',
                        },
                        {
                            label: 'Perfect comments',
                            backgroundColor: 'rgb(60,162,43)',
                            borderColor: 'rgb(60,162,43)',
                            fill: true,
                            data: @js($perfectCommentsData),
                            yAxisID: 'y',
                        }
                    ]
                },
                options: {
                    animation: false,
                    normalized: true,
                    parsing: false,
                    plugins: {
                        tooltip: {
                            mode: 'index'
                        }
                    },
                    elements: {
                        point: {
                            radius: 0
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            stacked: true,
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            min: 0,
                            max: 100,
                            type: 'linear',
                            display: true,
                            position: 'right',
                            ticks: {
                                callback: (tick) => tick.toString() + '%',
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    }
                }
            }
        );
    </script>

</x-layout.template.base>
