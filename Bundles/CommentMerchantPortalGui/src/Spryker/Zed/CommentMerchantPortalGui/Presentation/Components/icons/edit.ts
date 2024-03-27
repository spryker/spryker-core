import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
<path d="M2.0334 12H2.71673L10.0667 4.65004L9.3834 3.96671L2.0334 11.3167V12ZM12.2501 3.93337L10.1001 1.78337L10.7167 1.16671C10.9278 0.944485 11.1917 0.833374 11.5084 0.833374C11.8251 0.833374 12.089 0.93893 12.3001 1.15004L12.9334 1.78337C13.1445 1.98337 13.2445 2.22782 13.2334 2.51671C13.2223 2.8056 13.1223 3.05004 12.9334 3.25004L12.2501 3.93337ZM11.5501 4.63337L3.1334 13.05H0.983398V10.9L9.40006 2.48337L11.5501 4.63337ZM9.7334 4.30004L9.3834 3.96671L10.0667 4.65004L9.7334 4.30004Z" />
</svg>
`;

@NgModule({
    providers: [provideIcons([IconEditModule])],
})
export class IconEditModule {
    static icon = 'edit';
    static svg = svg;
}
