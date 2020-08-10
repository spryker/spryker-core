import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g fill-rule="nonzero" fill="none"><path d="M.622 13.478a10.004 10.004 0 0018.756 0L10 12.608l-9.378.87z" fill="#FFDA44"/><path d="M10 0C5.7 0 2.035 2.714.622 6.522l9.378.87 9.378-.87A10.004 10.004 0 0010 0z" fill="#000"/><path d="M.622 6.522A9.979 9.979 0 000 10c0 1.223.22 2.395.622 3.478h18.756A9.979 9.979 0 0020 10c0-1.223-.22-2.395-.622-3.478H.622z" fill="#D80027"/></g></svg>
`;

@NgModule({
  providers: [provideIcons([IconGermanyModule])],
})
export class IconGermanyModule {
  static icon = 'germany';
  static svg = svg;
}
