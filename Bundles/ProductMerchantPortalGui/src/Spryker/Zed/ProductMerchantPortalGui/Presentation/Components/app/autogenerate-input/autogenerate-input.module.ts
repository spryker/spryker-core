import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { InputModule } from '@spryker/input';
import { CheckboxModule } from '@spryker/checkbox';
import { FormItemModule } from '@spryker/form-item';
import { AutogenerateInputComponent } from './autogenerate-input.component';

@NgModule({
    imports: [CommonModule, InputModule, CheckboxModule, FormItemModule],
    declarations: [AutogenerateInputComponent],
    exports: [AutogenerateInputComponent],
})
export class AutogenerateInputModule {}
