import { DoBootstrap, NgModule, ApplicationRef } from '@angular/core';

import { appBootstrapProvider } from './app-bootstrap';

@NgModule({
    providers: [appBootstrapProvider()],
})
export class RootMerchantPortalModule implements DoBootstrap {
    /* tslint:disable:no-empty */
    ngDoBootstrap(appRef: ApplicationRef): void {}
}
