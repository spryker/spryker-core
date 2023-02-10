import { DoBootstrap, NgModule, ApplicationRef } from '@angular/core';

import { appBootstrapProvider } from './app-bootstrap';

@NgModule({
    providers: [appBootstrapProvider()],
})
export class RootMerchantPortalModule implements DoBootstrap {
    /* eslint-disable */
    ngDoBootstrap(appRef: ApplicationRef): void {}
    /* eslint-enable */
}
