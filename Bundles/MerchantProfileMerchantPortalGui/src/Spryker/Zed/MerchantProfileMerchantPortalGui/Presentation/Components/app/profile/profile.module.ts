import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';

import { ProfileComponent } from './profile.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [ProfileComponent],
    exports: [ProfileComponent],
})
export class ProfileModule {}
