import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { IconUnitedStatesModule, IconGermanyModule } from '../icons';

import { MpProfileComponent } from './mp-profile.component';

@NgModule({
	imports: [CommonModule, CollapsibleModule],
	declarations: [MpProfileComponent],
	exports: [MpProfileComponent],
})
export class MpProfileModule {
}
