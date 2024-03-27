import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
  <g clip-path="url(#clip0_320_13454)">
    <path d="M6 1C4.065 1 2.5 2.565 2.5 4.5C2.5 7.125 6 11 6 11C6 11 9.5 7.125 9.5 4.5C9.5 2.565 7.935 1 6 1ZM3.5 4.5C3.5 3.12 4.62 2 6 2C7.38 2 8.5 3.12 8.5 4.5C8.5 5.94 7.06 8.095 6 9.44C4.96 8.105 3.5 5.925 3.5 4.5Z" fill="#71747C"/>
    <path d="M6 5.75C6.69036 5.75 7.25 5.19036 7.25 4.5C7.25 3.80964 6.69036 3.25 6 3.25C5.30964 3.25 4.75 3.80964 4.75 4.5C4.75 5.19036 5.30964 5.75 6 5.75Z" fill="#71747C"/>
  </g>
  <defs>
    <clipPath id="clip0_320_13454">
      <rect width="12" height="12" fill="white"/>
    </clipPath>
  </defs>
</svg>
`;

@NgModule({
    providers: [provideIcons([IconAddressModule])],
})
export class IconAddressModule {
    static icon = 'address';
    static svg = svg;
}
