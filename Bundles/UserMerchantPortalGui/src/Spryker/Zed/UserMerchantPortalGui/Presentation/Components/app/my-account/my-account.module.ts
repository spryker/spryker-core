import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { MyAccountComponent } from './my-account.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [MyAccountComponent],
    exports: [MyAccountComponent],
})
export class MyAccountModule {}
