@props(['seriesData' => [], 'name' => 'apex-chart' ])

@php
    $seriesData = array_merge([
      'chart' => [
        'type' => "area",
        'stacked' => true,
        'height' => 300,
        'foreColor' => "#999",
        'dropShadow' => [
           'enabled' => false,
        ],
        'toolbar' => [
            'show' => false,
        ],
      ],
      'colors' => ['#00E396', '#0090FF'],
      'stroke' => [
        'curve' => "smooth",
        'width' => 3
      ],
      'dataLabels' => [
        'enabled' => false
      ],
      'markers' => [
        'size' => 0,
        'strokeColor' => "#fff",
        'strokeWidth' => 3,
        'strokeOpacity' => 1,
        'fillOpacity' => 1,
        'hover' => [
          'size' => 6
        ]
      ],
      'xaxis' => [
        'type' => "datetime",
        'axisBorder' => [
          'show' => false
        ],
        'axisTicks' => [
          'show' => false
        ],
        'labels' => [
          'offsetX' => 15,
          'offsetY' => 0
        ],
        'tooltip' => [
          'enabled' => false
        ]
      ],
      'yaxis' => [
        'labels' => [
          'offsetX' => 0,
          'offsetY' => -10
        ],
        'tooltip' => [
          'enabled' => false
        ]
      ],
      'grid' => [
        'strokeColor' => "#000",
        'padding' => [
            'top' => 0,
            'left' => -30,
            'right' => 0,
            'bottom' => -5
        ]
      ],
      'legend' => [
        'show' => false,
      ],
    ], $seriesData);
    $seriesData = json_encode($seriesData)
@endphp

<div 
    id="chart"
    wire:key="{{ rand() }}"
    x-data="{
        data: {{ $seriesData }},
        init(){

            this.data.chart.events = {
                mounted: function (chartContext, config) {
                    renderLegend(chartContext);
                },
                updated: function (chartContext, config) {
                    renderLegend(chartContext);
                }
            }

            var chart = new ApexCharts(document.querySelector('#chart-{{ $name }}'), this.data);

            chart.render();

            // reset custom zoom button
            var resetZoomButton = document.querySelector('#chart-reset-zoom-{{ $name }}');
            resetZoomButton.addEventListener('click', function () {
              chart.resetSeries()
                        
            });

            // generate custom legend view
            function renderLegend(chartContext) {
                var legendContainer = document.querySelector('#chart-legend-{{ $name }}');

                if (!legendContainer) return;

                legendContainer.innerHTML = ''; // Clear any existing legend items
                
                chartContext.w.globals.seriesNames.forEach(function (seriesName, i) {
                
                    var seriesColor = chartContext.w.config.colors[i];
                    var legendItem = document.createElement('div');
                    legendItem.classList.add('flex', 'items-center', 'm-2', 'cursor-pointer');
                    legendItem.setAttribute('data-series-index', i);

                    var colorBox = document.createElement('span');
                    colorBox.id = seriesName
                    colorBox.classList.add('w-4', 'h-4', 'inline-block', 'mr-2');
                    colorBox.style.backgroundColor = seriesColor;

                    var labelText = document.createElement('span');
                    labelText.textContent = seriesName;

                    legendItem.appendChild(colorBox);
                    legendItem.appendChild(labelText);
                    legendContainer.appendChild(legendItem);

                    // Initial visibility state
                    var isCollapsed = chartContext.w.globals.collapsedSeriesIndices.includes(i);
                    if (isCollapsed) {
                        legendItem.classList.add('opacity-50');
                    }

                    legendItem.addEventListener('click', function () {
                      
                        var seriesIndex = parseInt(this.getAttribute('data-series-index'), 10);
                        var isCurrentlyCollapsed = chartContext.w.globals.collapsedSeriesIndices.includes(seriesIndex);

                        chart.toggleSeries(chartContext.w.globals.seriesNames[seriesIndex]);

                        if (isCurrentlyCollapsed) {
                            this.classList.remove('opacity-50');
                        } else {
                            this.classList.add('opacity-50');
                        }
                    });
                });
            }

        }
    }"
>
  <div id="chart-{{ $name }}" class="apex-chart"></div>
</div>

