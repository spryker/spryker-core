import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ButtonModule } from '@spryker/button';
import { IconModule } from '@spryker/icon';
import { SpinnerModule } from '@spryker/spinner';
import { TextareaModule } from '@spryker/textarea';
import { IconSendModule } from '../../icons';
import { AddCommentComponent } from './add-comment.component';

@NgModule({
    imports: [SpinnerModule, CommonModule, IconModule, IconSendModule, TextareaModule, ButtonModule],
    declarations: [AddCommentComponent],
    exports: [AddCommentComponent],
})
export class AddCommentModule {}
