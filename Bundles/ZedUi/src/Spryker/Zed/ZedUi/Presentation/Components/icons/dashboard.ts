import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 11V3H11V11H3ZM3 21V13H11V21H3ZM13 11V3H21V11H13ZM13 21V13H21V21H13Z" fill="currentColor"/>
    </svg>
`;

@NgModule({
    providers: [provideIcons([IconDashboardModule])],
})
export class IconDashboardModule {
    static icon = 'dashboard';
    static svg = svg;
}
