import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
<?xml version="1.0" encoding="UTF-8"?>
<svg viewBox="0 0 22 20">
  <defs>
    <path d="M12.314 20.03a1.744 1.744 0 01-1.71 1.757H4.018a1.726 1.726 0 01-1.245-.506 1.76 1.76 0 01-.515-1.252v-6.613A1.397 1.397 0 011 12.026v-.139c0-.113.012-.226.038-.336l1.03-4.214a1.392 1.392 0 011.346-1.066h17.015c.646 0 1.206.45 1.352 1.085l.949 4.163c.024.104.037.21.037.318v.196a1.396 1.396 0 01-1.389 1.39h-.302v6.77a1.6 1.6 0 01-3.199 0v-6.77h-5.563zm-3.771-5.833H6.079c-.694 0-1.257.569-1.257 1.27v2.48c0 .702.563 1.27 1.257 1.27h2.464c.694 0 1.257-.568 1.257-1.27v-2.48c0-.701-.563-1.27-1.257-1.27zM19.892 2a1.6 1.6 0 011.6 1.6v.03a1.6 1.6 0 01-1.6 1.6H3.882a1.6 1.6 0 01-1.6-1.6V3.6a1.6 1.6 0 011.6-1.6h16.01z" id="a"/>
  </defs>
  <g transform="translate(-1 -2)" fill="none" fill-rule="evenodd">
    <mask id="b" fill="#fff">
      <use xlink:href="#a"/>
    </mask>
    <use fill="currentColor" xlink:href="#a"/>
    <g mask="url(#b)" fill="currentColor">
      <path d="M0 0h24v24H0z"/>
    </g>
  </g>
</svg>
`;

@NgModule({
    providers: [provideIcons([IconProfileModule])],
})
export class IconProfileModule {
    static icon = 'profile';
    static svg = svg;
}
