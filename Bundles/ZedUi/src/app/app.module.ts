import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { AppComponent } from './app.component';
import { ZedAuthFooterComponent } from './zed-auth-footer/zed-auth-footer.component';
import { ZedLayoutCentralComponent } from './zed-layout-central/zed-layout-central.component';

@NgModule({
    declarations: [
        AppComponent,
        ZedAuthFooterComponent,
        ZedLayoutCentralComponent
    ],
    imports: [
        BrowserModule
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [ZedAuthFooterComponent, ZedLayoutCentralComponent];
}
