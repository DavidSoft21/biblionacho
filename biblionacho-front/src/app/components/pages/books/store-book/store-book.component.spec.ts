import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoreBookComponent } from './store-book.component';

describe('StoreBookComponent', () => {
  let component: StoreBookComponent;
  let fixture: ComponentFixture<StoreBookComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [StoreBookComponent]
    });
    fixture = TestBed.createComponent(StoreBookComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
