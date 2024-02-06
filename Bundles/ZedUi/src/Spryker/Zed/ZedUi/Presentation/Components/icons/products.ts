import { NgModule } from '@angular/core';
import { provideIcons } from '@spryker/icon';

const svg = `
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11 21.725V12.575L3 7.95V15.975C3 16.3417 3.0875 16.675 3.2625 16.975C3.4375 17.275 3.68333 17.5167 4 17.7L11 21.725ZM13 21.725L20 17.7C20.3167 17.5167 20.5625 17.275 20.7375 16.975C20.9125 16.675 21 16.3417 21 15.975V7.95L13 12.575V21.725ZM16.975 7.975L19.925 6.25L13 2.275C12.6833 2.09167 12.35 2 12 2C11.65 2 11.3167 2.09167 11 2.275L9.025 3.4L16.975 7.975ZM12 10.85L14.975 9.15L7.05 4.55L4.05 6.275L12 10.85Z" fill="currentColor"/>
    </svg>
`;

@NgModule({
    providers: [provideIcons([IconProductsModule])],
})
export class IconProductsModule {
    static icon = 'products';
    static svg = svg;
}
