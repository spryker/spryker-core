import { NgModule } from '@angular/core';

import { ZedAuthFooterModule } from '../zed-auth-footer/zed-auth-footer.module';
import { ZedLayoutCentralComponent } from './zed-layout-central.component';

@NgModule({
    imports: [ZedAuthFooterModule],
    declarations: [ZedLayoutCentralComponent],
    exports: [ZedLayoutCentralComponent],
})
export class ZedLayoutCentralModule {}
