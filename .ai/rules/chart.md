---
paths:
  - 'resources/views/components/chart/**'
---

# Chart

## Dashboard charts are single-series by design
Both charts render one series in --color-viz-series (#2a78d6), validated for contrast and CVD against a white surface. Do not add a second hue to a chart: the four status colours fail colourblind separation (red vs green ΔE 4.1 deutan), which is why status hue lives only in <x-status-badge>, always beside its text label.

If you add a multi-series chart, run the dataviz validator first and assign categorical hues in fixed order. Every chart also ships a "View as table" <details> — keep that when editing.
