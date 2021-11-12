import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';
import { ImageSets, ImageSetNames, ImageSetTitles, ImageSetError, ImageDataError, ImageData } from './types';
import { IconPlusModule } from '@spryker/icon/icons';
import { ButtonVariant } from '@spryker/button';
import { IconDeleteModule } from '../../icons';
import { EmptyImageSet, EmptyImageSetData } from './empty-image-set';

@Component({
    selector: 'mp-image-sets',
    templateUrl: './image-sets.component.html',
    styleUrls: ['./image-sets.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-image-sets',
    },
})
export class ImageSetsComponent {
    @Input() @ToJson() imageSets?: ImageSets[];
    @Input() @ToJson() names?: ImageSetNames;
    @Input() @ToJson() titles?: ImageSetTitles;
    @Input() @ToJson() errors?: ImageSetError[];

    addButtonIcon = IconPlusModule.icon;
    removeButtonIcon = IconDeleteModule.icon;
    addImageSetButtonVariant = ButtonVariant.Outline;
    addImageButtonVariant = ButtonVariant.Outline;
    deleteImageSetButtonVariant = ButtonVariant.CriticalOutline;

    addImageSet(): void {
        this.imageSets = [new EmptyImageSet(), ...this.imageSets];

        if (this.errors?.length) {
            this.errors = [null, ...this.errors];
        }
    }

    removeImageSet(setIndex: number): void {
        this.imageSets = this.imageSets.filter((item, index) => index !== setIndex);

        if (this.errors?.length) {
            this.errors = this.errors.filter((item, index) => index !== setIndex);
        }
    }

    addImageSetData(setIndex: number): void {
        this.imageSets[setIndex] = { ...this.imageSets[setIndex] };
        this.imageSets[setIndex].images = [...this.imageSets[setIndex].images, new EmptyImageSetData()];
    }

    removeImageSetData(setIndex: number, imageIndex: number): void {
        this.imageSets[setIndex] = { ...this.imageSets[setIndex] };
        this.imageSets[setIndex].images = this.imageSets[setIndex].images.filter((item, index) => index !== imageIndex);

        if (!this.errors?.length || !this.errors[setIndex]?.images) {
            return;
        }

        this.errors[setIndex] = { ...this.errors[setIndex] };
        this.errors[setIndex].images = this.errors[setIndex].images.filter((item, index) => index !== imageIndex);
    }

    getNameErrors(errors: ImageSetError[], setIndex: number): string | undefined {
        return errors?.[setIndex]?.name;
    }

    getImageSetErrors(errors: ImageSetError[], setIndex: number, imageIndex: number): Partial<ImageDataError> {
        return errors?.[setIndex]?.images?.[imageIndex] ?? {};
    }

    trackByImageSet(index: number, set: ImageSets): string {
        return `${index}-${set?.idProductImageSet}-${set?.originalIndex}`;
    }

    trackByImageSetImages(index: number, images: ImageData): string {
        return `${index}-${images?.idProductImage}`;
    }
}
